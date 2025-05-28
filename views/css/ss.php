<style>
    
:root {
    --primary-color: #ff4d4d;
    --secondary-color: #ffb347;
    --dark-color: #333333;
    --light-color: #f9f9f9;
    --border-color: #e0e0e0;
    --shadow-color: rgba(0, 0, 0, 0.15);
    --header-height: 80px;
    --sidebar-width: 280px;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    background-color: #f5f5f5;
    color: var(--dark-color);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Header Styles */
.admin-header {
    background-color: #ffffff;
    box-shadow: 0 2px 10px var(--shadow-color);
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
    height: var(--header-height);
    display: flex;
    align-items: center;
    padding: 0 20px;
}

.admin-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    height: 100%;
}

.menu-toggle {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: var(--dark-color);
    margin-right: 20px;
    display: none;
}

.logo-header {
    display: flex;
    align-items: center;
    gap: 15px;
}

.logo-header img {
    width: 160px;
    height: 60px;
    border-radius: 1px;
    object-fit: cover;
}

.logo-header h1 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark-color);
    white-space: nowrap;
}

.user-nav {
    display: flex;
    align-items: center;
    gap: 20px;
}

.welcome-msg {
    font-weight: 500;
    color: #555;
}

.user-actions {
    display: flex;
    gap: 10px;
}

.btn-user-action {
    padding: 8px 15px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 5px;
}

.btn-user-action i {
    font-size: 0.9rem;
}

.btn-user-action:hover {
    background-color: #f0f0f0;
}

.btn-logout {
    color: var(--primary-color);
}

/* Sidebar Styles */
.sidebar-container {
    position: fixed;
    top: var(--header-height);
    left: 0;
    bottom: 0;
    width: var(--sidebar-width);
    background-color: white;
    box-shadow: 2px 0 10px var(--shadow-color);
    transition: all 0.3s ease;
    z-index: 999;
    overflow-y: auto;
    transform: translateX(-100%);
}

.sidebar-container.active {
    transform: translateX(0);
}

.sidebar-logo {
    padding: 20px;
    text-align: center;
    border-bottom: 1px solid var(--border-color);
    display: none;
}

.sidebar-logo img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin-bottom: 10px;
    object-fit: cover;
}

.sidebar-menu {
    list-style: none;
    padding: 20px 0;
}

.sidebar-menu li {
    margin-bottom: 5px;
}

.sidebar-menu a {
    display: flex;
    align-items: center;
    padding: 12px 25px;
    color: var(--dark-color);
    text-decoration: none;
    transition: all 0.3s ease;
    gap: 15px;
    position: relative;
}

.sidebar-menu a:hover, .sidebar-menu a.active {
    background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
    color: white;
}

.sidebar-menu a i {
    width: 20px;
    text-align: center;
    font-size: 18px;
}

.sidebar-menu a::after {
    content: "";
    position: absolute;
    right: 20px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: var(--primary-color);
    opacity: 0;
    transition: all 0.3s ease;
}

.sidebar-menu a.active::after {
    opacity: 1;
}

.menu-label {
    font-size: 14px;
    color: #757575;
    padding: 15px 25px 5px;
    text-transform: uppercase;
    font-weight: 600;
    display: block;
}

/* Main Content Styles */
.main-content {
    margin-top: var(--header-height);
    padding: 30px;
    flex: 1;
    transition: all 0.3s ease;
    width: 100%;
}

.main-content.sidebar-active {
    margin-left: var(--sidebar-width);
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.page-title {
    font-size: 24px;
    font-weight: 600;
}
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--dark-color);
            margin-right: 15px;
        }
        
        /* Sidebar */
        .sidebar-container {
            position: fixed;
            top: 80px;
            left: 0;
            bottom: 0;
            width: 280px;
            background-color: white;
            box-shadow: 2px 0 10px var(--shadow-color);
            transition: all 0.3s ease;
            z-index: 999;
            overflow-y: auto;
        }
        
        .sidebar-logo {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
            display: none;
        }
        
        .sidebar-logo img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
            object-fit: cover;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: var(--dark-color);
            text-decoration: none;
            transition: all 0.3s ease;
            gap: 15px;
            position: relative;
        }
        
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .sidebar-menu a i {
            width: 20px;
            text-align: center;
            font-size: 18px;
        }
        
        .sidebar-menu a::after {
            content: "";
            position: absolute;
            right: 20px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--primary-color);
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu a.active::after {
            opacity: 1;
        }
        
        .menu-label {
            font-size: 14px;
            color: #757575;
            padding: 15px 25px 5px;
            text-transform: uppercase;
            font-weight: 600;
            display: block;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 280px;
            margin-top: 80px;
            padding: 30px;
            flex: 1;
            transition: all 0.3s ease;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .page-title {
            font-size: 24px;
            font-weight: 600;
        }
        
        .btn {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 4px 6px rgba(255, 77, 77, 0.3);
            letter-spacing: 1px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn:hover {
            background: linear-gradient(to right, #ff3333, #ffa726);
            box-shadow: 0 6px 10px rgba(255, 77, 77, 0.4);
            transform: translateY(-2px);
        }
        
        .btn:active {
            transform: translateY(1px);
            box-shadow: 0 2px 4px rgba(255, 77, 77, 0.3);
        }
        
        /* Cards */
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px var(--shadow-color);
            position: relative;
            overflow: hidden;
        }
        
        .card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }
        
        .card-title {
            font-size: 16px;
            color: #757575;
            margin-bottom: 10px;
        }
        
        .card-value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .card-icon {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 40px;
            opacity: 0.2;
            color: var(--primary-color);
        }
        
        /* Tables */
        .table-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px var(--shadow-color);
            padding: 20px;
            margin-bottom: 30px;
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        th {
            background-color: var(--light-color);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            color: #757575;
        }
        
        tr:hover {
            background-color: var(--light-color);
        }
        
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-active {
            background-color: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
        }
        
        .status-inactive {
            background-color: rgba(244, 67, 54, 0.1);
            color: #F44336;
        }
        
        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #757575;
            margin: 0 5px;
            transition: all 0.3s ease;
            font-size: 16px;
        }
        
        .action-btn:hover {
            color: var(--primary-color);
        }
        
        /* Forms */
        .form-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px var(--shadow-color);
            padding: 30px;
            margin-bottom: 30px;
            
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: var(--light-color);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255, 77, 77, 0.2);
            background-color: white;
        }
        
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .card {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }
        .card:nth-child(4) { animation-delay: 0.4s; }
        
        /* Custom styles for specific modules */
        .proyectos-section, .recursos-section, .evaluacion-section {
            margin-bottom: 40px;
        }
        
        .section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border-color);
            color: var(--primary-color);
        }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #ff3333;
        }
@media (max-width: 1200px) {
    .sidebar-container {
        width: 250px;
    }
    
    .main-content.sidebar-active {
        margin-left: 250px;
    }
}

@media (max-width: 992px) {
    .menu-toggle {
        display: block;
    }
    
    .sidebar-logo {
        display: block;
    }
    
    .user-nav {
        margin-left: auto;
    }
    
    .welcome-msg {
        display: none;
    }
}

@media (max-width: 768px) {
    .admin-header {
        padding: 0 15px;
    }
    
    .logo-header h1 {
        font-size: 1.2rem;
    }
    
    .btn-user-action span {
        display: none;
    }
    
    .btn-user-action i {
        font-size: 1.1rem;
    }
    
    .main-content {
        padding: 20px 15px;
    }
    
    .cards-container {
        grid-template-columns: 1fr;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
}

@media (max-width: 576px) {
    .logo-header h1 {
        display: none;
    }
}

/* Footer Styles */
footer {
    background-color: #f1f1f1;
    text-align: center;
    padding: 15px;
    font-size: 14px;
    color: #757575;
    margin-top: auto;
}
/*estilos del formulario */
form {
    margin: 25% 25%;
    background-color: white;
    border-radius: 16px;
    padding: 50px 40px;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    position: relative;
    overflow: hidden;
    min-width: 400px;
    max-width: 600px;
    margin-top: 80px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

form::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, #ff4d4d, #ffb347);
}

/* Campos de entrada */
input[type="text"],
input[type="password"],
input[type="email"],
#cargo{
    width: 100%;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
    background-color: #f9f9f9;
    color: #333333;
}

input::placeholder {
    color: #999999;
}

input[type="text"]:focus,
input[type="password"]:focus, 
input[type="email"]:focus,
#cargo:focus {
    outline: none;
    border-color: #ff4d4d;
    box-shadow: 0 0 0 3px rgba(255, 77, 77, 0.2);
    background-color: white;
}

/* Botón de envío */
input[type="submit"], button {
    width: 100%;
    background: linear-gradient(to right, #ff4d4d, #ffb347);
    color: white;
    border: none;
    padding: 15px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 10px;
    box-shadow: 0 4px 6px rgba(255, 77, 77, 0.3);
    letter-spacing: 1px;
    text-transform: uppercase;
}

input[type="submit"]:hover {
    background: linear-gradient(to right, #ff3333, #ffa726);
    box-shadow: 0 6px 10px rgba(255, 77, 77, 0.4);
    transform: translateY(-2px);
}

input[type="submit"]:active {
    transform: translateY(1px);
    box-shadow: 0 2px 4px rgba(255, 77, 77, 0.3);
}
/* Estilos del footer */
footer {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: #f1f1f1;
    text-align: center;
    padding: 10px 0;
    font-size: 14px;
    color: #757575;
}
/* Estilos responsivos */
@media (max-width: 500px) {
    header {
        padding: 1rem;
    }
    nav {
        flex-direction: column;
        align-items: flex-start;
    }
    nav ul {
        flex-direction: column;
        gap: 1rem;
        width: 100%;
        margin-top: 1rem;
    }
    nav ul li {
        width: 100%;
        border-bottom: 1px solid #ecf0f1;
        padding-bottom: 0.5rem;
    }
    nav ul li:last-child {
        border-bottom: none;
    }
    form {
        padding: 30px 20px;
    }
    main {
        padding: 15px;
    }
    .logo-header h1 {
        font-size: 24px;
    }
    .logo-header img {
        width: 60px;
        height: 60px;
    }
}
</style>