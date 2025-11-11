/* STYLE */
/* ============ BURGER MENU ============ */
    .burger-menu {
      display: flex;
      cursor: pointer;
      background: rgba(15,23,42,0.04);
      border: none;
      padding: 8px;
      z-index: 1001;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 8px;
      transition: background-color var(--transition), transform var(--transition);
      box-shadow: 0 1px 0 rgba(0,0,0,0.03);
    }
    
    .burger-menu:hover {
      background: rgba(22, 163, 74, 0.1);
      transform: scale(1.05);
    }
    
    .burger-menu img {
      width: 24px;
      height: 24px;
      opacity: 1 !important;
      filter: none !important;
    }
    
    .burger-menu img.menu-icon { display: block; }
    
    @media (prefers-color-scheme: dark) {
      .burger-menu { background: rgba(255,255,255,0.04); }
    }

/* ============ NAVIGATION ============ */
    .nav {
      position: fixed;
      top: 0;
      right: -100%;
      height: 100vh;
      width: 80%;
      max-width: 300px;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      flex-direction: column;
      padding: 80px 24px 24px;
      transition: right var(--transition);
      box-shadow: var(--shadow);
      z-index: 1000;
      display: flex;
      gap: 8px;
    }

    .nav.active {
      right: 0;
    }

    .nav a {
      color: var(--muted);
      text-decoration: none;
      font-weight: 600;
      padding: 12px 20px;
      border-radius: 25px;
      transition: all var(--transition);
      font-size: 0.9rem;
      position: relative;
      overflow: hidden;
    }

    .nav a::before {
      content: '';
      position: absolute;
      inset: 0;
      background: var(--gradient-primary);
      opacity: 0;
      transition: opacity var(--transition);
      z-index: -1;
    }

    .nav a:hover {
      color: var(--white);
      transform: translateY(-2px);
      box-shadow: var(--shadow-hover);
    }

    .nav a:hover::before {
      opacity: 1;
    }

<header class="header">
    <div class="brand">
      <a href="../index.html" class="logo">
        <img src="../img/logo-KKP.png" alt="Logo SIMANGRO"/>
      </a>
      <div class="sitename">SIMANGRO</div>
    </div>
  
    <button class="burger-menu" aria-label="Toggle menu" aria-expanded="false">
      <img src="../img/menu-dark.png" alt="Menu" class="menu-icon">
    </button>
  
    <nav class="nav" aria-label="Menu utama">
      <a href="../#about">Beranda</a>
      <a href="index.html">Jenis Mangrove</a>
      <a href="../peta sebaran.html">Peta Sebaran</a>
      <a href="../quiz.html">Quiz</a>
    </nav>
  </header>

  <script>
  // ============ BURGER MENU ============
    (function() {
      const burger = document.querySelector('.burger-menu');
      const nav = document.querySelector('.nav');
      const links = nav.querySelectorAll('a');
      let isOpen = false;
    
      function toggle() {
        isOpen = !isOpen;
        burger.setAttribute('aria-expanded', isOpen);
        nav.classList.toggle('active', isOpen);
      }
    
      burger.addEventListener('click', toggle);
    
      document.addEventListener('click', (e) => {
        if (isOpen && !nav.contains(e.target) && !burger.contains(e.target)) {
          toggle();
        }
      });
    
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && isOpen) toggle();
      });
    
      links.forEach(link => {
        link.addEventListener('click', () => {
          if (isOpen) toggle();
        });
      });
    
      window.addEventListener('resize', () => {
        if (window.innerWidth > 768 && isOpen) toggle();
      });
    })();
  </script>