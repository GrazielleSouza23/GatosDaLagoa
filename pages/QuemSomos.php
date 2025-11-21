<?php
require_once '../includes/header.php';
?>

<section class="quem-somos-section" style="padding: 100px 0; background: linear-gradient(135deg, #e8f8f5 0%, #f0fdf4 100%);">

    <!-- DECORAÇÃO: patinhas -->
    <div class="paw-decorations" aria-hidden="true">
        <!-- Lado esquerdo (subindo para direita) -->
        <div class="paw paw-left" style="--top:18%; --left:3%; --delay:0s;"></div>
        <div class="paw paw-left" style="--top:33%; --left:6%; --delay:0.6s;"></div>
        <div class="paw paw-left" style="--top:48%; --left:3%; --delay:1.2s;"></div>

        <!-- Lado direito (subindo para esquerda) -->
        <div class="paw paw-right" style="--top:18%; --right:3%; --delay:0.3s;"></div>
        <div class="paw paw-right" style="--top:33%; --right:6%; --delay:0.9s;"></div>
        <div class="paw paw-right" style="--top:48%; --right:3%; --delay:1.5s;"></div>
    </div>


    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Quem Somos</h2>
        </div>

        <div class="adoption-content">
            <div class="adoption-image">
                <div class="adoption-image-wrapper">
                    <img src="../assets/images/uploads/<?php echo htmlspecialchars($configs['imagem_missao']['valor'] ?? 'Gato1.jpg'); ?>" alt="Voluntária cuidando de um gato">
                </div>
            </div>
            <div class="adoption-text">
                <h3><?php echo htmlspecialchars($configs['titulo_missao']['valor'] ?? ''); ?></h3>
                <p class="adoption-description"><?php echo htmlspecialchars($configs['missao']['valor'] ?? ''); ?></p>
            </div>
        </div>

    </div>
</section>
    <!-- Onda-->
    <svg viewBox="0 0 1200 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" style="margin-top: -38px; position: absolute;">
        <path d="M0,50 C200,100 400,20 600,70 C800,120 1000,40 1200,90 L1200,120 L0,120 Z" fill="#ffffff"></path>
    </svg>

<section style="padding: 100px 0; background: var(--color-white);">

    <!-- DECORAÇÃO: patinhas -->
    <div class="paw-decorations" aria-hidden="true">
        <!-- Lado esquerdo (subindo para direita) -->
        <div class="paw paw-left" style="--top:100%; --left:3%; --delay:0s;"></div>
        <div class="paw paw-left" style="--top:115%; --left:6%; --delay:0.6s;"></div>
        <div class="paw paw-left" style="--top:130%; --left:3%; --delay:1.2s;"></div>

        <!-- Lado direito (subindo para esquerda) -->
        <div class="paw paw-right" style="--top:100%; --right:3%; --delay:0.3s;"></div>
        <div class="paw paw-right" style="--top:115%; --right:6%; --delay:0.9s;"></div>
        <div class="paw paw-right" style="--top:130%; --right:3%; --delay:1.5s;"></div>
    </div>

    <div class="container">

        <div class="adoption-content alternate-layout">
            <div class="adoption-text">
                <h3><?php echo htmlspecialchars($configs['titulo_reconhecimento']['valor'] ?? ''); ?></h3>
                <p class="adoption-description">
                <?php echo htmlspecialchars($configs['reconhecimento']['valor'] ?? ''); ?>
                </p>
            </div>
            <div class="adoption-image">
                <div class="adoption-image-wrapper">
                    <img src="../assets/images/uploads/<?php echo htmlspecialchars($configs['imagem_reconhecimento']['valor'] ?? 'Gato2.jpg'); ?>" 
                         alt="Documento de utilidade pública">
                </div>
            </div>
        </div>
    </div>

</section>

<!-- onda no topo da seção "Nosso Trabalho" — preenchida com a cor da seção -->
<div class="wave-top-lower">
  <svg viewBox="0 0 1200 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
    <path d="M0,10 C200,80 400,10 600,50 C800,90 1000,20 1200,60 L1200,120 L0,120 Z"
          fill="#F5F5F5"></path>
  </svg>
</div>

<section style="padding: 70px 0; background: var(--color-light);">

    <!-- DECORAÇÃO: patinhas -->
    <div class="paw-decorations" aria-hidden="true">
        <!-- Lado esquerdo (subindo para direita) -->
        <div class="paw paw-left" style="--top:145%; --left:3%; --delay:0s;"></div>
        <div class="paw paw-left" style="--top:160%; --left:6%; --delay:0.6s;"></div>
        <div class="paw paw-left" style="--top:175%; --left:3%; --delay:1.2s;"></div>

        <!-- Lado direito (subindo para esquerda) -->
        <div class="paw paw-right" style="--top:145%; --right:3%; --delay:0.3s;"></div>
        <div class="paw paw-right" style="--top:160; --right:6%; --delay:0.9s;"></div>
        <div class="paw paw-right" style="--top:175%; --right:3%; --delay:1.5s;"></div>
    </div>

        <div class="paw-decorations" aria-hidden="true">
        <!-- Lado esquerdo (subindo para direita) -->
        <div class="paw paw-left" style="--top:190%; --left:3%; --delay:0s;"></div>
        <div class="paw paw-left" style="--top:205%; --left:6%; --delay:0.6s;"></div>
        <div class="paw paw-left" style="--top:220%; --left:3%; --delay:1.2s;"></div>

        <!-- Lado direito (subindo para esquerda) -->
        <div class="paw paw-right" style="--top:190%; --right:3%; --delay:0.3s;"></div>
        <div class="paw paw-right" style="--top:205; --right:6%; --delay:0.9s;"></div>
        <div class="paw paw-right" style="--top:220%; --right:3%; --delay:1.5s;"></div>
    </div>

    <div class="container">
        <div class="adoption-content">
            <div class="adoption-image">
                <div class="adoption-image-wrapper">
                    <img src="../assets/images/uploads/<?php echo htmlspecialchars($configs['imagem_trabalho']['valor'] ?? 'Gato3.jpeg'); ?>" 
                         alt="Ponto de alimentação para gatos no parque">
                </div>
            </div>
            <div class="adoption-text">
                <h3>Nosso Trabalho</h3>
                <p class="adoption-description">
                    <?php echo htmlspecialchars($configs['trabalho']['valor'] ?? 'Gato3.jpeg'); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<?php
require_once '../includes/footer.php';
?>