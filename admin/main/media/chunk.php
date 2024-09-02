<script src="/admin/main/media/upload.js"></script>
<h2>Upload a File</h2>
    <input type="file" id="fileInput" multiple>
    <button id="uploadButton" class="button">Upload File</button>

    <p id="fileInfo"></p>
<script>
/*
    document.getElementById('fileInput').addEventListener('change', function(event) {
        // Get the selected file
        file = event.target.files[0];

        if (file) {
            totalChunks = Math.ceil(file.size / chunkSize);
            currentChunk = 0;

            // Enable the upload button
            document.getElementById('uploadButton').disabled = false;
        }
    });

    document.getElementById('uploadButton').addEventListener('click', function() {
        if (file) {
            const xhrfile = '/admin/main/media/xhr_chunk.php'; // Your server-side script for handling uploads
            // Start uploading the first chunk
            uploadChunk(0, xhrfile);
        }
    });
    */
const uploadManager = createUploadManager("/admin/main/media/xhr_chunk.php");
document.getElementById('uploadButton').addEventListener('click', function() {
    uploadManager.uploadFiles();
});

</script>
