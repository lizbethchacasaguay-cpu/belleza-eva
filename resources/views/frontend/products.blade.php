<!DOCTYPE html>
<html>
<head>
    <title>Detalle del producto</title>
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a href="/" class="navbar-brand">Belleza Eva</a>
    </div>
</nav>

<div class="container mt-4">
    <h2 id="name"></h2>
    <img id="image" class="img-fluid mb-3" />
    <p id="description"></p>
    <h4 id="price"></h4>
</div>

<script>
    const id = window.location.pathname.split('/').pop();

    fetch(`http://127.0.0.1:8000/api/products/${id}`)
        .then(res => res.json())
        .then(product => {
            document.getElementById("name").innerText = product.name;
            document.getElementById("description").innerText = product.description;
            document.getElementById("price").innerText = "$" + product.price;
            document.getElementById("image").src = product.image_url;
        });
</script>

</body>
</html>
