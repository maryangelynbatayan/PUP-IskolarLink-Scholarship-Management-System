// Responsive Navbar Toggle
document.addEventListener("DOMContentLoaded", () => {
    const nav = document.querySelector("nav");
    const menuToggle = document.createElement("button");
    menuToggle.innerText = "Menu";
    menuToggle.style.cssText = `
        background-color: #660000;
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        display: none;
        position: absolute;
        top: 10px;
        right: 20px;
    `;
    nav.appendChild(menuToggle);

    const links = Array.from(nav.querySelectorAll("a"));
    let isMenuOpen = false;

    menuToggle.addEventListener("click", () => {
        isMenuOpen = !isMenuOpen;
        links.forEach(link => {
            link.style.display = isMenuOpen ? "block" : "none";
        });
    });

    // Adjust menu for screen size
    const handleResize = () => {
        if (window.innerWidth <= 768) {
            menuToggle.style.display = "block";
            links.forEach(link => (link.style.display = "none"));
        } else {
            menuToggle.style.display = "none";
            links.forEach(link => (link.style.display = "inline-block"));
        }
    };

    handleResize(); // Initial check
    window.addEventListener("resize", handleResize);
});

// Smooth Scroll for Anchor Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener("click", function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute("href")).scrollIntoView({
            behavior: "smooth"
        });
    });
});

// Add 'in-view' class to features when in viewport
const features = document.querySelector(".features");
const observer = new IntersectionObserver(
    ([entry]) => {
        if (entry.isIntersecting) {
            features.classList.add("in-view");
        }
    },
    { threshold: 0.2 }
);
observer.observe(features);

// Sign Up 
document.addEventListener("DOMContentLoaded", () => {
    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("confirm-password");
    const showPasswordCheckbox = document.getElementById("show-password");
    const errorMessage = document.getElementById("password-error");

    // Function to check if passwords match
    const checkPasswordsMatch = () => {
        if (passwordInput.value === "") {
            errorMessage.textContent = "Password cannot be empty.";
            errorMessage.hidden = false;
        } else if (confirmPasswordInput.value && confirmPasswordInput.value !== passwordInput.value) {
            errorMessage.textContent = "The password you entered does not match. Please try again.";
            errorMessage.hidden = false;
        } else {
            errorMessage.hidden = true;
        }
    };

    // Function to toggle password visibility
    const togglePasswordVisibility = () => {
        const inputType = showPasswordCheckbox.checked ? "text" : "password";
        passwordInput.type = inputType;
        confirmPasswordInput.type = inputType;
    };

    // Event listeners
    passwordInput.addEventListener("input", checkPasswordsMatch);
    confirmPasswordInput.addEventListener("input", checkPasswordsMatch);
    showPasswordCheckbox.addEventListener("change", togglePasswordVisibility);
});