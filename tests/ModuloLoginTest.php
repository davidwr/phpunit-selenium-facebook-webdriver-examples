<?php
class ModuloLoginTest extends PHPUnit_Framework_TestCase
{
	private $driver;
	protected function setUp()
	{
		$this->driver = RemoteWebDriver::create(
			"http://localhost:4444/wd/hub",
			DesiredCapabilities::firefox()
			);
		$this->driver->manage()->window()->maximize();
	}

	// Versões utilizadas
	// PHPUnit 3.7.21 by Sebastian Bergmann.
	// PHP 5.6.21 (cli) (built: Apr 27 2016 20:13:54)
	// Composer version 1.1.1 2016-05-17 12:25:44

    /**
	 * @test
	 * @group Navegacao
	*/
	public function navegacaoEntrePaginas()
	{
		$this->driver->get("http://quickloja.qualister.info/");
		$this->assertContains('Login', $this->driver->getTitle());
		$this->driver->navigate()->to("http://google.com");
		$this->assertContains('Google', $this->driver->getTitle());
		$this->driver->navigate()->refresh();
		$this->assertContains('Google', $this->driver->getTitle());
		$this->driver->navigate()->back();
		$this->assertContains('Login', $this->driver->getTitle());
		$this->driver->navigate()->forward();
		$this->assertContains('Google', $this->driver->getTitle());
	}

	/**
	 * @test
	 * @group Login
	*/
	public function pegaElementoEMostraText()
	{
		$this->driver->get("http://quickloja.qualister.info/");
		$inputLogin = $this->driver->findElement(WebDriverBy::name("usuariologin"));
		$this->assertContains('Qualister', $inputLogin->getText());
	}

    /**
	 * @test
	 * @group Login
	*/
	public function apresentarTitulosDaPagina()
	{
		$this->driver->get("http://quickloja.qualister.info/");
		$tituloPagina = $this->driver->findElement(WebDriverBy::tagName("h2"));
		$this->assertContains('Login | QuickLoja', $this->driver->getTitle());
		$this->assertContains('QuickLoja', $tituloPagina->getText());
		
		$entrar = $this->driver->findElement(WebDriverBy::xpath("//*[local-name()='button' and text()='Entrar']"));
		$login = $this->driver->findElement(WebDriverBy::xpath("//*[@id='usuariologin']"));
		$senha = $this->driver->findElement(WebDriverBy::xpath("//*[@id='usuariosenha']"));

		$this->assertTrue('Entrar'==$entrar->getText());
		$this->assertTrue('text'==$login->getAttribute('type'));
		$this->assertTrue('usuariosenha'==$senha->getAttribute('name'));
	}

	/**
	 * @test
	 * @group Produto
	*/
	public function autenticarSeNaPaginaCadastrarProduto()
	{
		// CENARIO 1
		$this->driver->get("http://quickloja.qualister.info/");
		$tituloPagina = $this->driver->findElement(WebDriverBy::tagName("h2"));
		$this->assertContains('Login | QuickLoja', $this->driver->getTitle());
		$this->assertContains('QuickLoja', $tituloPagina->getText());	
		
		$entrar = $this->driver->findElement(WebDriverBy::xpath("//*[local-name()='button' and text()='Entrar']"));
		$login = $this->driver->findElement(WebDriverBy::xpath("//*[@id='usuariologin']"));
		$senha = $this->driver->findElement(WebDriverBy::xpath("//*[@id='usuariosenha']"));

		$login->clear();
		$login->sendKeys('teste');
		$senha->sendKeys('123');
		$entrar->click();
		$this->assertContains('Administração', $this->driver->getTitle());

		$menuModulos = $this->driver->findElement(WebDriverBy::xpath("//*[contains(text(),'Gerenciar')]"));
		$menuModulos->click();		

		$menuProduto = $this->driver->findElement(WebDriverBy::xpath("//*[local-name()='a' and text()='Produto']"));
		$menuProduto->click();

		$botaoNovoProduto = $this->driver->findElement(WebDriverBy::xpath("//*[local-name()='a' and text()='Novo produto']"));
		$botaoNovoProduto->click();

		$nomeProduto = $this->driver->findElement(WebDriverBy::xpath("//*[@name='produtonome']"));
		$nomeProduto->sendKeys('PRODUTO DE TESTE DO DAVID');
		$descricaoProduto = $this->driver->findElement(WebDriverBy::xpath("//*[@name='produtodescricao']"));
		$descricaoProduto->sendKeys('DESCRICAO DO PRODUTO DE TESTE');
		$valorProduto = $this->driver->findElement(WebDriverBy::xpath("//*[@name='produtovalor']"));
		$valorProduto->sendKeys('1');	

		$abaCategoria = $this->driver->findElement(WebDriverBy::xpath("//*[local-name()='a' and text()='Categorias']"));
		$abaCategoria->click();

		// NÃO CONSEGUI FAZER O DRAG AND DROP, PROCUREI VARIOS POSTS NA INTERNET, MAS NÃO FUNCIONOU.
		// $categoriaSelecionada = $this->driver->findElement(WebDriverBy::xpath("//*[@id='3']"));
		// $categoriaSelecionadas = $this->driver->findElement(WebDriverBy::xpath("//*[@id='selecionadas']"));

		// $this->driver->moveto($categoriaSelecionada);
		// $this->driver->buttondown();
		// $this->driver->moveto($categoriaSelecionadas);
		// $this->driver->buttonup();

		$itensEstoque = $this->driver->findElement(WebDriverBy::xpath("//*[local-name()='a' and text()='Itens em estoque']"));
		$itensEstoque->click();

		$estoqueCor = $this->driver->findElement(WebDriverBy::xpath("//*[@id='estoqueCor']"));
		$estoqueCor->sendKeys('PRETO');
		$estoqueTamanho = $this->driver->findElement(WebDriverBy::xpath("//*[@id='estoqueTamanho']"));
		$estoqueTamanho->sendKeys('P');
		$estoqueMaterial = $this->driver->findElement(WebDriverBy::xpath("//*[@id='estoqueMaterial']"));
		$estoqueMaterial->sendKeys('COURO');
		$estoqueTipo = $this->driver->findElement(WebDriverBy::xpath("//*[@id='estoqueTipo']"));
		$estoqueTipo->sendKeys('ESPORTIVO');
		$estoqueQuantidade = $this->driver->findElement(WebDriverBy::xpath("//*[@id='estoqueQuantidade']"));
		$estoqueQuantidade->sendKeys('1');

		$botaoAdicionarEstoque = $this->driver->findElement(WebDriverBy::xpath("//*[@id='adicionar']"));
		$botaoAdicionarEstoque->click();

		$botaoGravarProduto = $this->driver->findElement(WebDriverBy::xpath("//*[local-name()='button' and text()='Gravar']"));
		$botaoGravarProduto->click();		

		$this->assertContains('http://quickloja.qualister.info/produto/index/sucesso', $this->driver->getCurrentUrl());		
	}

	/**
	 * @test
	 * @group Produto
	*/
	public function excluirProduto()
	{
		// CENARIO 2
		$this->driver->get("http://quickloja.qualister.info/");
		$tituloPagina = $this->driver->findElement(WebDriverBy::tagName("h2"));
		$this->assertContains('Login | QuickLoja', $this->driver->getTitle());
		$this->assertContains('QuickLoja', $tituloPagina->getText());	
		
		$entrar = $this->driver->findElement(WebDriverBy::xpath("//*[local-name()='button' and text()='Entrar']"));
		$login = $this->driver->findElement(WebDriverBy::xpath("//*[@id='usuariologin']"));
		$senha = $this->driver->findElement(WebDriverBy::xpath("//*[@id='usuariosenha']"));

		$login->clear();
		$login->sendKeys('teste');
		$senha->sendKeys('123');
		$entrar->click();
		$this->assertContains('Administração', $this->driver->getTitle());

		$menuModulos = $this->driver->findElement(WebDriverBy::xpath("//*[contains(text(),'Gerenciar')]"));
		$menuModulos->click();		

		$menuProduto = $this->driver->findElement(WebDriverBy::xpath("//*[local-name()='a' and text()='Produto']"));
		$menuProduto->click();

		$botaoExcluir = $this->driver->findElement(WebDriverBy::xpath("//*[contains(@onclick,'PRODUTO DE TESTE DO DAVID')]"));
		$botaoExcluir->click();

		$this->driver->wait()->until(
		  WebDriverExpectedCondition::alertIsPresent()
		);

		$this->driver->switchTo()->alert()->accept();
		$this->assertContains('http://quickloja.qualister.info/produto/excluirproduto', $this->driver->getCurrentUrl());	

	}

	protected function tearDown()
	{
		// Em minha máquina o método close() é undefined.
		// $this->driver->close();
	}	
}
?>