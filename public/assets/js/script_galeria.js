function filterGallery(category, btn) {
    const items = document.querySelectorAll('.admin-gallery-item');
    const buttons = document.querySelectorAll('.filter-btn');

    buttons.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const selectedCategory = category.trim().toLowerCase();

    items.forEach(item => {
        const itemCategory = (item.dataset.category || '').trim().toLowerCase();
        item.style.display = (selectedCategory === 'all' || itemCategory === selectedCategory) ? 'block' : 'none';
    });
}

function createModal() {
    let modal = document.createElement('div');
    modal.id = 'imageModal';
    modal.setAttribute('role', 'dialog');
    modal.setAttribute('aria-modal', 'true');
    modal.setAttribute('aria-labelledby', 'modalCaption');
    modal.classList.add('hidden');
    modal.innerHTML = `
        <div class="modal-overlay" onclick="closeImageModal()">
            <div class="modal-content" onclick="event.stopPropagation()">
                <button class="modal-close" aria-label="Fechar modal" onclick="closeImageModal()">&times;</button>
                <img class="modal-image" src="" alt="">
                <div id="modalCaption" class="modal-caption"></div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    
    const styleSheet = document.createElement('style');
    styleSheet.textContent = `
        #imageModal.hidden { display: none; }
        #imageModal {
            position: fixed; z-index: 9999; left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.9);
            display: flex; align-items: center; justify-content: center;
            padding: 20px;
        }
        .modal-overlay {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
        }
        .modal-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
        }
        .modal-image {
            width: 100%;
            max-height: 80vh;
            object-fit: contain;
            border-radius: 10px;
        }
        .modal-close {
            position: absolute;
            top: -40px;
            right: 0;
            background: transparent;
            border: none;
            color: white;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .modal-close:hover {
            color: var(--primary-green);
        }
        .modal-caption {
            color: white;
            text-align: center;
            padding: 15px 0;
            font-size: 1.1rem;
        }
        body.modal-open {
            overflow: hidden;
        }
    `;
    document.head.appendChild(styleSheet);

    return modal;
}

function openImageModal(src, title) {
    let modal = document.getElementById('imageModal') || createModal();
    const modalImage = modal.querySelector('.modal-image');
    const modalCaption = modal.querySelector('.modal-caption');

    modalImage.src = src;
    modalImage.alt = title;
    modalCaption.textContent = title;
    
    modal.classList.remove('hidden');
    document.body.classList.add('modal-open');
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('modal-open');
    }
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});


/*Galeria Pública*/
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('imageModal');

    if (!modal) {
        return;
    }

    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');

    const closeButton = document.getElementById('imageModalClose');
    const backdrop = modal.querySelector('.image-modal-backdrop');

    const storyImages = document.querySelectorAll('.story-image');


    // ==========================================
    // Abrir modal
    // ==========================================

    function openModal(storyImage) {

        const image = storyImage.dataset.image;
        const title = storyImage.dataset.title || '';
        const description = storyImage.dataset.description || '';

        modalImage.src = image;
        modalImage.alt = title;

        modalTitle.textContent = title;

        // Mostra a descrição somente dentro do modal
        modalDescription.textContent = description;

        // Esconde o <p> se não houver descrição
        if (description.trim() === '') {
            modalDescription.style.display = 'none';
        } else {
            modalDescription.style.display = 'block';
        }

        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');

        document.body.classList.add('modal-open');

        // Coloca o foco no botão de fechar
        closeButton.focus();
    }


    // ==========================================
    // Fechar modal
    // ==========================================

    function closeModal() {

        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');

        document.body.classList.remove('modal-open');

        // Limpa a imagem para evitar que fique carregada
        modalImage.src = '';
        modalImage.alt = '';

        modalTitle.textContent = '';
        modalDescription.textContent = '';
    }


    // ==========================================
    // Clique nas imagens
    // ==========================================

    storyImages.forEach(function (storyImage) {

        storyImage.addEventListener('click', function () {
            openModal(this);
        });


        // Permite abrir com Enter ou Espaço
        storyImage.addEventListener('keydown', function (event) {

            if (event.key === 'Enter' || event.key === ' ') {

                event.preventDefault();

                openModal(this);
            }

        });

    });


    // ==========================================
    // Botão X
    // ==========================================

    closeButton.addEventListener('click', closeModal);


    // ==========================================
    // Clique fora do conteúdo
    // ==========================================

    backdrop.addEventListener('click', closeModal);


    // ==========================================
    // Tecla ESC
    // ==========================================

    document.addEventListener('keydown', function (event) {

        if (
            event.key === 'Escape' &&
            modal.classList.contains('active')
        ) {
            closeModal();
        }

    });

});