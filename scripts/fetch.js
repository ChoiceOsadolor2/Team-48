// // Example: login
// function login(email, password) {
//   fetch("http://127.0.0.1:8000/api/login", {
//     method: "POST",
//     headers: { "Content-Type": "application/json" },
//     body: JSON.stringify({ email, password })
//   })
//   .then(res => res.json())
//   .then(data => console.log("Login response:", data))
//   .catch(err => console.error(err));
// }

// // Example: run on homepage load
// if (window.location.pathname.endsWith("index.html")) {
//   getProducts();
// }
