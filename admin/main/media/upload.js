function createUploadManager(xhrfile) {
    let totalSize = 0;
    let uploadedSize = 0;
    let totalFiles = 0;
    let currentFileIndex = 0;

    function uploadChunk(file, start, chunkSize, currentChunk, totalChunks, retryCount = 0) {
        const maxRetries = 3;
        const chunk = file.slice(start, start + chunkSize);
        const formData = new FormData();
        formData.append('file', chunk);
        formData.append('chunkNumber', currentChunk + 1);
        formData.append('totalChunks', totalChunks);
        formData.append('fileName', file.name);

        $.ajax({
            url: xhrfile,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                currentChunk++;
                uploadedSize += chunk.size;
                const progress = Math.round((uploadedSize / totalSize) * 100);
                updateProgressBar(progress);

                if (currentChunk < totalChunks) {
                    uploadChunk(file, currentChunk * chunkSize, chunkSize, currentChunk, totalChunks);
                } else {
                    console.log(file.name + ' upload complete');
                    currentFileIndex++;
                    setTimeout(() => uploadNextFile(), 2000); // Wait 2 seconds before uploading the next file
                }
            },
            error: function() {
                if (retryCount < maxRetries) {
                    console.log('Retrying chunk upload...');
                    uploadChunk(file, start, chunkSize, currentChunk, totalChunks, retryCount + 1);
                } else {
                    console.error('Failed to upload chunk after several retries');
                }
            }
        });
    }

    function uploadNextFile() {
        if (currentFileIndex < totalFiles) {
            const file = document.getElementById('fileInput').files[currentFileIndex];
            const chunkSize = 1024 * 1024; // 1MB chunks
            const totalChunks = Math.ceil(file.size / chunkSize);
            const currentChunk = 0;
            uploadChunk(file, currentChunk * chunkSize, chunkSize, currentChunk, totalChunks);
        } else {
            console.log('All files uploaded successfully');
        }
    }

    function uploadFiles() {
        const files = document.getElementById('fileInput').files;
        totalFiles = files.length;
        totalSize = Array.from(files).reduce((sum, file) => sum + file.size, 0);
        uploadedSize = 0;
        currentFileIndex = 0;

        if (totalFiles > 0) {
            uploadNextFile();
        }
    }
    return {
        uploadFiles: uploadFiles
    };
}

// Usage
