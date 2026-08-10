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
