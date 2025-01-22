<div class="container">
    <h1>Files</h1>
    <div class="upload-container">
        <input type="file" id="file-upload" accept="image/*" multiple>
        <label for="file-upload">Upload Images</label>
    </div>

    <main>
        <div class="grid" id="image-grid">
        </div>
    </main>

    <script>
        const fileUpload = document.getElementById('file-upload');
        const imageGrid = document.getElementById('image-grid');

        fileUpload.addEventListener('change', async (event) => {
            const files = event.target.files;

            for (const file of files) {
                const formData = new FormData();
                formData.append('image', file);

                try {
                    const response = await fetch('<?php echo Config::get("URL"); ?>upload/upload', {
                        method: 'POST',
                        body: formData
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.success) {
                            const fileId = data.fileId;

                            const imageUrl = '<?php echo Config::get("URL"); ?>upload/serveImage/' + fileId;

                            const gridItem = document.createElement('div');
                            gridItem.className = 'grid-item';
                            gridItem.innerHTML = `<img src="${imageUrl}" alt="Uploaded Image">`;
                            imageGrid.appendChild(gridItem);
                        } else {
                            console.error('Upload failed:', data.message);
                        }
                    } else {
                        console.error('Image upload failed:', response.statusText);
                    }
                } catch (error) {
                    console.error('Error uploading image:', error);
                }
            }
        });


        document.addEventListener('DOMContentLoaded', async () => {
            const imageGrid = document.getElementById('image-grid');

            try {
                const response = await fetch('<?php echo Config::get("URL"); ?>upload/listImages');
                if (response.ok) {
                    const images = await response.json();

                    images.forEach(image => {
                        const fileId = image.fileId;

                        const imageUrl = '<?php echo Config::get("URL"); ?>upload/serveImage/' + fileId;

                        const gridItem = document.createElement('div');
                        gridItem.className = 'grid-item';
                        gridItem.innerHTML = `<img src="${imageUrl}" alt="Uploaded Image">`;
                        imageGrid.appendChild(gridItem);
                    });
                } else {
                    console.error('Failed to fetch images:', response.statusText);
                }
            } catch (error) {
                console.error('Error fetching images:', error);
            }
        });
    </script>
</div>