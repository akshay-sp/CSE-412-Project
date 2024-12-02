document.getElementById('search-button').addEventListener('click', function() {
    const query = document.getElementById('search-bar').value;

    fetch(`http://localhost:8000/api/searchPhones.php?query=${query}`)
        .then(response => response.json())
        .then(data => {
            const results = document.getElementById('results');
            results.innerHTML = '';
            data.forEach(phone => {
                results.innerHTML += `<div>${phone.brand} ${phone.model} - $${phone.price}</div>`;
            });
        })
        .catch(error => console.error('Error:', error));
});