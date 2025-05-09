<style>
:root {
    --primary-color: #3f51b5; /* Azul índigo */
    --primary-light: #757de8;
    --primary-dark: #002984;
    --secondary-color: #ffffff;
    --accent-color: #ff4081; /* Rosa */
    --text-color: #333333;
    --text-light: #757575;
    --background-color: #f5f5f5;
    --input-border: #e0e0e0;
    --shadow-color: rgba(0, 0, 0, 0.1);
    --error-color: #f44336;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    color: var(--text-color);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

main {
    width: 100%;
    max-width: 400px;
    padding: 20px;
}

/* Estilos del formulario */
form {
    background-color: var(--secondary-color);
    border-radius: 12px;
    padding: 40px 30px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
}

form::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
}

/* Logo o título */
form::after {
    content: "INVILARA";
    display: block;
    text-align: center;
    font-size: 24px;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 30px;
    letter-spacing: 1px;
}

/* Campos de entrada */
input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid var(--input-border);
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
    background-color: #f9f9f9;
}

input[type="text"]:focus,
input[type="password"]:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(63, 81, 181, 0.2);
    background-color: #ffffff;
}

/* Botón de envío */
input[type="submit"] {
    width: 100%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    border: none;
    padding: 15px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 10px;
    box-shadow: 0 4px 6px rgba(63, 81, 181, 0.3);
}

input[type="submit"]:hover {
    background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
    box-shadow: 0 6px 10px rgba(63, 81, 181, 0.4);
    transform: translateY(-2px);
}

input[type="submit"]:active {
    transform: translateY(1px);
    box-shadow: 0 2px 4px rgba(63, 81, 181, 0.3);
}

/* Enlaces de recuperación */
.recuperacion {
    display: block;
    text-align: center;
    color: var(--text-light);
    text-decoration: none;
    font-size: 14px;
    margin: 10px 0;
    transition: all 0.3s ease;
}

.recuperacion:hover {
    color: var(--primary-color);
    text-decoration: underline;
}

/* Animaciones */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

input, .recuperacion {
    animation: fadeIn 0.5s ease-out forwards;
}

input[type="text"] { animation-delay: 0.1s; }
input[type="password"] { animation-delay: 0.2s; }
.recuperacion:nth-of-type(1) { animation-delay: 0.3s; }
.recuperacion:nth-of-type(2) { animation-delay: 0.4s; }
input[type="submit"] { animation-delay: 0.5s; }

/* Responsive */
@media (max-width: 480px) {
    form {
        padding: 30px 20px;
    }
    
    main {
        padding: 15px;
    }
}
</style>