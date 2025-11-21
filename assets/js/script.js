// ===================================
// Menu Mobile Toggle
// ===================================
const menuToggle = document.getElementById('menuToggle');
const navMenu = document.getElementById('navMenu');

if (menuToggle) {
    menuToggle.addEventListener('click', () => {
        navMenu.classList.toggle('active');

        // Animação do ícone hambúrguer
        const spans = menuToggle.querySelectorAll('span');
        if (navMenu.classList.contains('active')) {
            spans[0].style.transform = 'rotate(45deg) translate(7px, 7px)';
            spans[1].style.opacity = '0';
            spans[2].style.transform = 'rotate(-45deg) translate(7px, -7px)';
            menuToggle.setAttribute('aria-label', 'Fechar menu');
        } else {
            spans[0].style.transform = 'none';
            spans[1].style.opacity = '1';
            spans[2].style.transform = 'none';
            menuToggle.setAttribute('aria-label', 'Abrir menu');
        }
    });
}

// Fechar menu ao clicar em um link
const navLinks = document.querySelectorAll('.nav-link');
navLinks.forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            navMenu.classList.remove('active');
            const spans = menuToggle.querySelectorAll('span');
            spans[0].style.transform = 'none';
            spans[1].style.opacity = '1';
            spans[2].style.transform = 'none';
        }
    });
});

// ===================================
// Alternar Visibilidade da Senha (Login)
// ===================================
document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('senha');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function () {
            // Alterna o atributo 'type' entre 'password' e 'text'
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Opcional: Altera o ícone/texto do botão
            this.textContent = (type === 'password' ? '👁️' : '🔒'); 
        });
    }
});

// ===================================
// Scroll Suave
// ===================================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// ===================================
// Header Scroll Effect
// ===================================
const header = document.querySelector('.header');
let lastScroll = 0;

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 100) {
        header.style.boxShadow = '0 5px 30px rgba(0, 0, 0, 0.15)';
    } else {
        header.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
    }
    
    lastScroll = currentScroll;
});

// ===================================
// Animação de Entrada dos Elementos
// ===================================
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Elementos para animar
const animateElements = document.querySelectorAll('.activity-card, .story-card, .contact-item');
animateElements.forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
});

// ===================================
// Contador de Números (se houver)
// ===================================
function animateCounter(element, target, duration = 2000) {
    let start = 0;
    const increment = target / (duration / 16);
    
    const timer = setInterval(() => {
        start += increment;
        if (start >= target) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(start);
        }
    }, 16);
}

// ===================================
// Carrossel de Histórias (opcional)
// ===================================
const storiesCarousel = document.querySelector('.stories-carousel');
if (storiesCarousel && window.innerWidth <= 768) {
    let isDown = false;
    let startX;
    let scrollLeft;

    storiesCarousel.addEventListener('mousedown', (e) => {
        isDown = true;
        startX = e.pageX - storiesCarousel.offsetLeft;
        scrollLeft = storiesCarousel.scrollLeft;
    });

    storiesCarousel.addEventListener('mouseleave', () => {
        isDown = false;
    });

    storiesCarousel.addEventListener('mouseup', () => {
        isDown = false;
    });

    storiesCarousel.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - storiesCarousel.offsetLeft;
        const walk = (x - startX) * 2;
        storiesCarousel.scrollLeft = scrollLeft - walk;
    });
}

// ===================================
// Lazy Loading de Imagens
// ===================================
const images = document.querySelectorAll('img[data-src]');
const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const img = entry.target;
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
            imageObserver.unobserve(img);
        }
    });
});

images.forEach(img => imageObserver.observe(img));

// ===================================
// Formulário de Contato (se houver)
// ===================================
const contactForm = document.querySelector('.contact-form');
if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Validação básica
        const formData = new FormData(contactForm);
        let isValid = true;
        
        formData.forEach((value, key) => {
            if (!value.trim()) {
                isValid = false;
            }
        });
        
        if (isValid) {
            // Aqui você pode adicionar a lógica de envio do formulário
            alert('Mensagem enviada com sucesso! Entraremos em contato em breve.');
            contactForm.reset();
        } else {
            alert('Por favor, preencha todos os campos.');
        }
    });
}


// ===================================
// Botão de Voltar ao Topo
// ===================================
const backToTopButton = document.createElement('button');
backToTopButton.type = 'button';
backToTopButton.innerHTML = '↑';
backToTopButton.className = 'back-to-top';
backToTopButton.style.cssText = `
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #65C5B2, #A8CF45);
    color: white;
    border: none;
    border-radius: 50%;
    font-size: 24px;
    cursor: pointer;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 999;
    box-shadow: 0 5px 20px rgba(101, 197, 178, 0.3);
`;

document.body.appendChild(backToTopButton);

window.addEventListener('scroll', () => {
    if (window.pageYOffset > 300) {
        backToTopButton.style.opacity = '1';
        backToTopButton.style.visibility = 'visible';
    } else {
        backToTopButton.style.opacity = '0';
        backToTopButton.style.visibility = 'hidden';
    }
});

backToTopButton.addEventListener('click', () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

backToTopButton.addEventListener('mouseenter', () => {
    backToTopButton.style.transform = 'translateY(-5px)';
    backToTopButton.style.boxShadow = '0 10px 30px rgba(101, 197, 178, 0.4)';
});

backToTopButton.addEventListener('mouseleave', () => {
    backToTopButton.style.transform = 'translateY(0)';
    backToTopButton.style.boxShadow = '0 5px 20px rgba(101, 197, 178, 0.3)';
});

// ===================================
// Copiar PIX
// ===================================

document.addEventListener("DOMContentLoaded", function () {
    const botaoCopiar = document.getElementById("btnCopiarPix");
    const spanPix = document.getElementById("pix");

    if (botaoCopiar && spanPix) {
        botaoCopiar.addEventListener("click", function () {
            const textoPix = spanPix.textContent.trim();

            // Tenta copiar com clipboard API
            if (navigator.clipboard) {
                navigator.clipboard.writeText(textoPix).then(function () {
                    alert("Chave PIX copiado com sucesso!");
                }).catch(function (err) {
                    console.error("Erro ao copiar:", err);
                    alert("Erro ao copiar a chave PIX.");
                });
            } else {
                // Fallback para navegadores antigos
                const textarea = document.createElement("textarea");
                textarea.value = textoPix;
                document.body.appendChild(textarea);
                textarea.select();
                try {
                    document.execCommand("copy");
                    alert("Chave PIX copiado com sucesso!");
                } catch (err) {
                    console.error("Erro ao copiar:", err);
                    alert("Erro ao copiar a chave PIX.");
                }
                document.body.removeChild(textarea);
            }
        });
    }
});


// ===================================
// Adicionar tópicos (Seção de Adoção)
// ===================================
document.getElementById('btnAddTopico').addEventListener('click', () => {
    const container = document.getElementById('lista-topicos');
    const count = container.querySelectorAll('.form-group').length + 1;
    const div = document.createElement('div');
    div.classList.add('form-group');
    div.innerHTML = `
        <label class="form-label">Tópico ${count}</label>
        <input type="text" name="topicos[]" class="form-input" placeholder="Digite o texto do tópico">
    `;
    container.appendChild(div);
});

document.getElementById('btnRemoveTopico').addEventListener('click', () => {
    const container = document.getElementById('lista-topicos');
    if (container.lastElementChild) container.removeChild(container.lastElementChild);
});


// ===================================
// Console Log
// ===================================
console.log('%c🐱 ONG Gatos da Lagoa Taquaral', 'color: #65C5B2; font-size: 20px; font-weight: bold;');
console.log('%cDesenvolvido com ❤️ para os gatinhos', 'color: #A8CF45; font-size: 14px;');
