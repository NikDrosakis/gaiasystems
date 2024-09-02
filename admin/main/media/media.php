<!-----------------MEDIA------------->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<style>
    /* Basic styling for the tabs */
    #pagination {
        margin-top: 20px;
        text-align: center;
    }

    .page-link, .prev-link, .next-link {
        margin: 0 5px;
        padding: 5px 10px;
        text-decoration: none;
        color: #007bff;
        cursor: pointer;
    }

    .page-link.active {
        font-weight: bold;
        color: #fff;
        background-color: #007bff;
        border-radius: 3px;
    }

    .prev-link, .next-link {
        font-weight: bold;
        color: #007bff;
        background-color: #f8f9fa;
        border-radius: 3px;
    }

    .prev-link:hover, .next-link:hover, .page-link:hover {
        text-decoration: underline;
    }
    .tab-container {
        width: 100%;
        margin-top: 20px;
    }
.folder{
    border-radius: 10%;
    border: 1px solid black;
width:140px;
    cursor:pointer;
    background: antiquewhite;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid #000;
    text-align: center;
    font-size: large;
}
    ul.tabs {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        justify-content: flex-start;
        border-bottom: 1px solid #ddd;
    }

    ul.tabs li {
        cursor: pointer;
        padding: 10px 20px;
        background: #f1f1f1;
        margin-right: 5px;
        border: 1px solid #ddd;
        border-bottom: none;
        border-radius: 3px 3px 0 0;
    }

    ul.tabs li.current {
        background: white;
        border-bottom: 1px solid white;
    }

    .tab-content {
        display: none;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 0 3px 3px 3px;
        background: white;
    }

    .tab-content.current {
        display: block;
    }
    .media-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    .media-gallery img {
        width: 140px;
        border:1px solid black;
        border-radius:10%;
        height: auto;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .media-gallery img.selected {
        border-color: #0073aa;
    }
</style>
    <h1>Media Management</h1>
    <?php
    // Fetch slides from the database
    $slides = $this->db->fa("SELECT * FROM wid_slideshow ORDER BY sort ASC, id ASC");
    ?>
    <ul class="tabs">
        <li class="tab-link current" data-tab="tab-2">Media Manager</li>
        <li class="tab-link" data-tab="tab-1">Upload Image</li>
        <li class="tab-link" data-tab="tab-3">Image from URL</li>
    </ul>
    <!-- Form for adding a new slide -->
    <div id="tab-2" class="tab-content current">
        <form id="chooseForm">
            <input type="hidden" name="a" value="upload_media">
            <input type="hidden" id="media_image" value="">

            <div class="media-gallery" id="mediaGallery">
                <!-- Media thumbnails will be populated here -->
            </div>

        </form>

        <div id="imagePreviewContainer">
            <img id="imagePreview" src="" alt="Image Preview" style="max-width:100%; height:auto; display:none; margin-top:10px;">
        </div>
    </div>
    <div id="tab-1" class="tab-content">
        <form id="uploadForm" enctype="multipart/form-data">
            <input type="hidden" name="a" value="upload_file">
            <label for="upload_image">Upload Image:</label>
            <input type="file" class="button" id="upload_image" name="upload_image" required>
            <button type="submit" class="button button-primary">Add Media</button>
        </form>
    </div>
    <div id="tab-3" class="tab-content">
        <form id="urlForm">
            <input type="hidden" name="a" value="upload_url">
            <label for="url_image">Image URL:</label>
            <input type="text" id="url_image" name="url_image" required>
            <button type="button" id="urlButton" class="button button-primary">Add Media</button>
        </form>
    </div>
    <div class="table-container">
        <table id="sortable" class="styled-table">
            <thead>
            <tr>
                <th>Sort</th>
                <th>Image</th>
                <th>filename</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($slides as $slide): ?>
                <tr id="slide_<?php echo $slide['id']; ?>">
                    <td class="sort-order"><?php echo $slide['sort']; ?></td>
                    <td><img src="/media/slideshow/<?php echo htmlspecialchars($slide['filename']); ?>" style="max-width:100px;"></td>
                    <td><?=$slide['filename']?></td>
                    <td><input type="checkbox" class="delete-checkbox" value="<?php echo $slide['id']; ?>"></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table></div>

<script>
    var folder="";
    // Initialize Sortable on the table body
    const sortable = new Sortable(document.querySelector('#sortable tbody'), {
        animation: 150,
        onEnd: function (/**Event*/evt) {
            let order = [];
            $('#sortable tbody tr').each(function(index, element) {
                const id = $(this).attr('id').replace('slide_', '');
                order.push({id: id, sort: index + 1});
            });

            // Send the new order to the server via AJAX
            $.ajax({
                url: '/widgets/slideshow/post_xhr.php',
                type: 'POST',
                data: { a:'order',order: order },
                success: function(response) {
                    console.log('Sort order updated successfully');
                },
                error: function(xhr, status, error) {
                    console.error('Error updating sort order:', error);
                }
            });
        }
    })

    // Capture the blur event when the user finishes editing a caption
        $(document).on('change', '.delete-checkbox', function() {
            if ($(this).is(':checked')) {
                let id = $(this).val();
                s.confirm("This image is going to be deleted. Are you sure?",res=>{
                    if(res)
                        $.ajax({
                            url: '/widgets/slideshow/post_xhr.php',
                            type: 'POST',
                            data: { a:'delete',delete: id },
                            success: function(response) {
                                console.log('Slide deleted successfully');
                                $(`#slide_${id}`).remove();  // Remove the row from the DOM
                            },
                            error: function(xhr, status, error) {
                                console.error('Error deleting slide:', error);
                            }
                        });
                })
            }
        })
    // Tab switching logic
    $('.tab-link').on('click', function() {
        var tab_id = $(this).attr('data-tab');
        $('.tab-link').removeClass('current');
        $('.tab-content').removeClass('current');
        $(this).addClass('current');
        $("#" + tab_id).addClass('current');
    });

    // Populate the media select dropdown with images from the media/slideshow folder
    function loadMedia(folder = '', page = 1, limit = 100){
    s.db.get({file:G.ADMIN_ROOT+'main/media/get_media.php?folder='+folder}, function(data) {
            const response = JSON.parse(data);
            const mediaGallery = $('#mediaGallery');
            const folderPath = folder ? `/media/${folder}` : '/media';
        const pagination = $('#pagination');
            // Clear previous thumbnails and folders
            mediaGallery.empty();
        pagination.empty();
            // Add navigation to the parent folder if not in the root
            if (folder && response.parentFolder !== '') {
                mediaGallery.append(`<div class="folder" data-folder="${response.parentFolder}">..</div>`);           
				}

        response.files.forEach(file => {
            const filePath = `/media${folder}/${file}`;
            mediaGallery.append(`<img src="${filePath}" data-filename="${file}" alt="${file}">`);
        });

        // Handle image click
        mediaGallery.on('click', 'img', function() {
            const selectedImage = $(this);
            const filePath = selectedImage.attr('src');
            // Update the preview
            $('#imagePreview').attr('src', filePath).show();
            // Highlight selected thumbnail
            mediaGallery.find('img').removeClass('selected');
            selectedImage.addClass('selected');
            // Optionally set the selected file name in a hidden input or variable
            $('#media_image').val(selectedImage.data('filename'));
        });

        // Generate pagination controls
        const totalPages = Math.ceil(response.totalFiles / limit);

        for (let i = 1; i <= totalPages; i++) {
            const pageLink = $(`<a href="#" class="page-link">${i}</a>`);
            if (i === page) {
                pageLink.addClass('active');
            }

            pageLink.on('click', function(e) {
                e.preventDefault();
                loadMedia(folder, i, limit);
            });

            pagination.append(pageLink);
        }

        // Add a "Previous" link
        if (page > 1) {
            const prevLink = $(`<a href="#" class="prev-link">Previous</a>`);
            prevLink.on('click', function(e) {
                e.preventDefault();
                loadMedia(folder, page - 1, limit);
            });
            pagination.prepend(prevLink);
        }

        // Add a "Next" link
        if (page < totalPages) {
            const nextLink = $(`<a href="#" class="next-link">Next</a>`);
            nextLink.on('click', function(e) {
                e.preventDefault();
                loadMedia(folder, page + 1, limit);
            });
            pagination.append(nextLink);
        }
    });
    }

    // Initialize with the first page of the root folder
    loadMedia('', 1, 100);

    //tab a
    $('#uploadForm').on('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        // Log FormData contents
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        console.log(formData.entries())
        $.ajax({
            url: '/widgets/slideshow/post_xhr.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                console.log(res)
                if (res.status=="success") {
                    appendSlideToTable(res);
                    $('#uploadForm')[0].reset();
                } else {
                    console.log(res.message);
                }
            },
            dataType: 'json'
        });
    });
    // Handle selection from media folder (Tab B)
    $('#chooseButton').on('click', function(event) {
        event.preventDefault();
        const filename = $('#media_image').val();
        const params={ a:"upload_media",filename: filename};
        console.log(params)
        $.post('/admin/main/media/post_media.php', params, function(res) {
            console.log(res);
            if(res.status=='success') {
                appendSlideToTable(res);
            }
        },'json');
    });

    // Handle adding an image from a URL (Tab C)
    $('#urlButton').on('click', function() {
        const url = $('#url_image').val();
        const caption = $('#caption_url').val();
        var params={ a:'upload_url',url: url, caption: caption }
        console.log(params)
        $.post('/widgets/slideshow/post_xhr.php', params, function(res) {
            if(res.status=='success') {
                appendSlideToTable(res);
            }
        },'json');
    });
    // Function to append slide to the table
    function appendSlideToTable(slide) {
        $('#sortable tbody').append(`
         <tr id="slide_${slide.id}">
        <td class="sort-order">${slide.sort}</td>
<td>${slide.sort}</td>
                <td><img src="/media/slideshow/${slide.filename}" alt="${slide.filename}" style="max-width: 100px;"></td>
                <td>${slide.filename}</td>
                <td contenteditable="true" class="editable-caption">${slide.caption}</td>
                <td><input type="checkbox" class="delete-checkbox" value="${slide.id}"></td>
            </tr>
        `);
    }
</script>