<!DOCTYPE html>
<html>
  <head>
    <meta charset="<?php echo $this->document->getCharset();?>" />
    <title><?php echo $this->document->getTitle(); ?></title>
    <meta name="ecidade:version" content="<?php echo $this->version; ?>">
    <?php echo $this->document->renderLinks(); ?>
    <base href="<?php echo ECIDADE_CURRENT_EXTENSION_REQUEST_PATH; ?>" />
      <link rel="stylesheet" href="public/css/app.css">
  </head>
  <body>

    <div class="topbar">

      <div class="user-nav">

          <div class="dropdown user-nav-item settings" id="documentos-atividades">
              <div class="dropdown-toggle icon-document">
                  <?php
                    if ($this->countDocumentos > 0) {
                        echo "<div class='notify'>{$this->countDocumentos}</div>";
                    }
                    ?>
              </div>
          </div>

        <div data-type="dropdown" class="dropdown user-nav-item settings">

          <div class="dropdown-toggle icon-config"></div>

          <ul class="dropdown-menu">
            <?php if ($this->showFallbackButton) : ?>
              <li><a href="desktop/fallback/" id="fallback">Usar versão antiga</a></li>
            <?php endif; ?>
            <li><a data-in-full-screen="false" id="fullscreen">Usar em tela cheia</a></li>
          </ul>
        </div>

        <div data-type="dropdown" class="dropdown user-nav-item user">

          <div class="dropdown-toggle">
            <span class="user-name"><?php echo $this->usuarioSistema->getLogin(); ?></span>
            <img class="user-picture" src="assets/img/topbar/user-picture-default.png">
          </div>

          <ul class="dropdown-menu">
            <li class="dropdown-item-no-fx">

              <div class="profile">

                <div class="user-info">

                  <div class="picture">
                    <img src="<?php echo ECIDADE_REQUEST_PATH . $this->caminhoFoto; ?>">
                  </div>

                  <div class="name"><span><?php echo \DBString::utf8_encode_all($this->usuarioSistema->getNome()); ?></span></div>
                </div>

                <div class="system-info">

                  <p>
                    <b>Base:</b>
                    <em class="base-name selectable"><?php echo $this->request->session()->get('DB_NBASE'); ?></em>
                  </p>
                  <p>
                    <b>Servidor:</b>
                    <em class="selectable">
                      <?php echo $this->request->session()->get('DB_servidor') . ':' .  $this->request->session()->get('DB_porta');  ?>
                    </em>
                  </p>

                </div>

              </div>

            </li>

            <li><a id="block">Bloquear</a></li>
            <li><a href="Window/logout" id="logout">Logout</a></li>

          </ul>

        </div>

      </div>

    </div>

    <div class="taskbar-container">

        <div class="taskbar-menu-button" title="Menu Principal">MENU</div>
        <ul class="taskbar-buttons" id="taskbar-buttons"></ul>

        <div title="Lista de janelas abertas" class="taskbar-buttons-modal" style="display:none;">

          <div class="icon">
            <span class="bar-1"></span>
            <span class="bar-2"></span>
            <span class="bar-3"></span>
          </div>

          <div class="content"></div>

        </div>

    </div>

    <div id="menu">

      <div class="menu-header">

        <div class="menu-actions">
          <div class="menu-action-home"><span></span></div>
          <div class="menu-action-search">
            <input name="menu-search" id="menu-search" />
            <span class="menu-search-icon"></span>
          </div>
        </div>

        <div class="menu-breadcrumb selectable">
          <ul id="menu-breadcrumb"></ul>
        </div>

        <div class="menu-resizer"></div>

        <div class="menu-close"><span>&times;</span></div>

      </div>

      <div class="menu-content">

        <div class="menu-pager menu-pager-left disabled">
          <div class="icon"></div>
        </div>

      <div class="menu-list-container">

        <div class="menu-list-title">Instituições</div>

          <div id="instituicoes" class="menu-list"></div>
      </div>

        <div class="menu-list-container divider-left">

          <div class="menu-list-title">Áreas</div>

          <div id="areas" class="menu-list"></div>

        </div>

        <div class="menu-list-container divider-left">

          <div class="menu-list-title">Módulos</div>

          <div id="modulos" class="menu-list"></div>

        </div>

        <div class="menu-pager menu-pager-right">
          <div class="icon"></div>
        </div>

      </div>

    </div>

    <script type="text/javascript">
      var ECIDADE_REQUEST_PATH = '<?php echo ECIDADE_REQUEST_PATH; ?>';
      var ECIDADE_DESKTOP = true;
    </script>
    <?php echo $this->document->renderScripts(); ?>
    <script src="public/js/app.js"></script>
    <script src="public/extension/package/desktop/views/desktop/index.js"></script>
    <?php
    $instituicaoLogada = is_null($this->instituicaoUsuario) ? 1 : $this->instituicaoUsuario;
    ?>
    <script>
        const btnDocumentos = document.getElementById("documentos-atividades");
        btnDocumentos.addEventListener('click', () => {
            var parametros = {
                action: 'con4_atividades_documentos.php',
                iInstitId: <?=$instituicaoLogada?>, // Instituição Prefeitura
                iAreaId: 4, // Area Patrimonial
                iModuloId: 604, // Módulo Protocolo
                lAtalhoDesktop: true
            }

            Desktop.Window.create('Andamento de Documentos', parametros);
            document.querySelector("#documentos-atividades .icon-document").innerHTML = "";
        });
    </script>
  </body>
</html>
