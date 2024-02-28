<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tratamento de imagens</title>

  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

  <link rel="stylesheet" href="/css/style_modulo_img.css">
</head>
<body>
  <main>
    <header>
      <div>
        <button id="btnResetFilters">Limpar Filtros</button>
      </div>
      <div>
        <button id="newImg">Nova Imagem</button>
        <button id="btnSalvar" style="background: #0081CF; color: white;">Salvar</button>
      </div>
    </header>
    <div class="img-content">
      <input type="file" accept="image/*" style="display: none;">
      <img src="nova-logo-mb.jpg" alt="" />
    </div>
    <footer>
      <div class="rotate-content">
        <button onclick="handleDirection('rotateRight')"><i class='bx bx-rotate-right'></i></button>
        <button onclick="handleDirection('rotateLeft')"><i class='bx bx-rotate-left'></i></i></button>
        <button onclick="handleDirection('reflectY')"><i class='bx bx-reflect-vertical'></i></button>
        <button onclick="handleDirection('reflectX')"><i class='bx bx-reflect-horizontal'></i></button>
      </div>
      <span id="spnRangeValue"></span>
      <input type="range" style="width: 80%;" />
      <div class="filters-content">
        <button id="filterDefault" class="active">Brilho</button>
        <button>Contraste</button>
        <button>Saturação</button>
        <button>Cinza</button>
        <button>Inversão</button>
      </div>
    </footer>
  </main>

  <script src="/scripts/script_modulo_img.js"></script>
</body>

</html>