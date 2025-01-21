document.getElementById('uploadForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('upload.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Изображения загружены!');
                location.reload(); // Перезагружаем страницу для отображения новых изображений
            }
        })
        .catch(error => console.error('Ошибка:', error));
});