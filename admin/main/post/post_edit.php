<style>
    /* Custom CSS for Admin Page */

    /* General Container Styling */
    .container {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    /* Page Title and Navigation Buttons */
    .pagetitle-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .pagetitle {
        font-size: 1.75rem;
        font-weight: bold;
        color: #343a40;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-align: center;
        flex-grow: 1;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: #fff;
        margin: 0 10px;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    /* Main Content Area */
    .main-content {
        flex: 1;
        margin-right: 20px;
    }

    /* Right Sidebar Styling */
    .sidebar {
        width: 300px;
        background-color: #ffffff;
        padding: 20px;
        border: 1px solid #ced4da;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Sidebar Sections */
    .sidebar h5 {
        font-weight: bold;
        color: #495057;
        margin-bottom: 15px;
    }

    .sidebar label {
        font-weight: 600;
        color: #495057;
    }

    /* Excerpt Styling */
    #excerpt {
        width: 100%;
    }

    /* Remove Quill Editor */
    .no-editor {
        font-family: inherit;
        font-size: inherit;
        line-height: inherit;
        padding: 0.375rem 0.75rem;
    }

    /* Form Inputs */
    .form-control {
        border-radius: 5px;
        border-color: #ced4da;
        box-shadow: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 5px rgba(128, 189, 255, 0.5);
    }

    /* Checkboxes and Radios */
    .gs-input[type="checkbox"] {
        margin-right: 10px;
    }

    /* Add File Button */
    .btn-success.btn-sm.fileinput-button {
        display: inline-block;
        margin-top: 10px;
    }

    .img-thumbnail {
        border-radius: 5px;
        margin-top: 10px;
    }

    /* Progress Bar */
    .progress-bar {
        background-color: #28a745;
    }

    /* Add New Tag and Save Button */
    #newtaxsave, #newtagsave {
        margin-top: 5px;
        border-radius: 5px;
        background-color: #17a2b8;
        border: none;
        color: #fff;
    }

    #newtaxsave:hover, #newtagsave:hover {
        background-color: #138496;
    }

    /* Additional Padding and Margins */
    .mb-4, .mt-4 {
        margin-bottom: 1.5rem !important;
    }

    .mb-3 {
        margin-bottom: 1rem !important;
    }

    .mt-3 {
        margin-top: 1rem !important;
    }

    /* Sidebar Alignment */
    .row {
        display: flex;
    }

    .col-md-6, .col-md-12 {
        padding-right: 15px;
    }
</style>

<div id="mainpage" class="container mt-4">
    <!-- Page Title and Navigation Buttons -->
    <div class="pagetitle-container mb-3">
        <span onclick="s.ui.goto(['previous','post','id',s.get.id,'/admin/post?id='])" class="btn btn-secondary">
            <i class="glyphicon glyphicon-chevron-left"></i> Previous
        </span>
        <div id="title" class="pagetitle">
            Architecture
            <a href="/architecture" target="_blank" class="btn btn-link" style="font-size: small;">
                Read View
            </a>
        </div>
        <span onclick="s.ui.goto(['next','post','id',s.get.id,'/admin/post?id='])" class="btn btn-secondary">
            Next <i class="glyphicon glyphicon-chevron-right"></i>
        </span>
    </div>

    <!-- Main Content and Sidebar Wrapper -->
    <div class="row">
        <!-- Main Content -->
        <div class="main-content">
            <!-- Image Uploader (Moved to top) -->
            <div class="imgBox mb-4">
                <div id="files" class="files">
                    <img src="https://vivalibro.com/media/40027562_10155955799054315_3272421117096099840_n.jpg" class="img-thumbnail" style="height:250px; width: 229px;">
                </div>
                <span class="btn btn-success btn-sm fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i> Add File
                    <input id="fileupload" type="file" name="files[]" multiple>
                </span>
                <br>
                <br>
                <div id="progress" class="progress">
                    <div class="progress-bar progress-bar-success"></div>
                </div>
                <div id="files" class="files"></div>
            </div>

            <!-- FORM FIELDS -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="id">ID</label>
                    <div class="form-control-static">1</div>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="uid">UID</label>
                    <div class="form-control-static">nikos</div>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="uri">URI</label>
                    <input class="form-control" name="uri" placeholder="URI" id="uri" type="text" value="architecture">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="title">Title</label>
                    <input class="form-control" name="title" placeholder="Title" id="title" type="text" value="Architecture">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="subtitle">Subtitle</label>
                    <input class="form-control" name="subtitle" placeholder="Subtitle" id="subtitle" type="text" value="This is the sub">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="sort">Sort</label>
                    <input class="form-control" name="sort" placeholder="Sort" id="sort" type="number" value="0">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="status">Status</label>
                    <select class="form-control" name="status" id="status">
                        <option value="0">Closed</option>
                        <option value="1">Inactive</option>
                        <option value="2" selected="selected">Active</option>
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="postgrpid">Post Group ID</label>
                    <select class="form-control" name="postgrpid" id="postgrpid">
                        <option value="1" selected="selected">Article</option>
                        <option value="2">Presentation</option>
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="excerpt">Excerpt</label>
                    <textarea class="form-control" name="excerpt" id="excerpt" rows="3">This is the excerpt</textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="content">Content</label>
                    <textarea class="form-control no-editor" name="content" id="content" rows="6">Gaia's purpose...</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="created">Created</label>
                    <div class="form-control-static">2017-01-03 17:23</div>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="modified">Modified</label>
                    <div class="form-control-static">2019-06-04 13:43</div>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="published">Published</label>
                    <div class="form-control-static">2017-01-03 17:23</div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="sidebar">
            <!-- Category Selection -->
            <h5>Category</h5>
            <div>
                <label class="gs-label" for="categories">Categories</label>
                <div id="post-categories" class="gs-input-container">
                    <input type="checkbox" class="gs-input" id="category1" name="category[]" value="1"> Category 1
                    <input type="checkbox" class="gs-input" id="category2" name="category[]" value="2"> Category 2
                    <input type="checkbox" class="gs-input" id="category3" name="category[]" value="3"> Category 3
                </div>
            </div>

            <!-- Tag Selection -->
            <h5>Tags</h5>
            <div>
                <label class="gs-label" for="tags">Tags</label>
                <div id="post-tags" class="gs-input-container">
                    <input type="checkbox" class="gs-input" id="tag1" name="tag[]" value="1"> Tag 1
                    <input type="checkbox" class="gs-input" id="tag2" name="tag[]" value="2"> Tag 2
                    <input type="checkbox" class="gs-input" id="tag3" name="tag[]" value="3"> Tag 3
                </div>
            </div>

            <!-- Add New Tag Button -->
            <button id="newtagsave" class="btn btn-primary btn-sm">Add New Tag</button>
        </div>
    </div>
</div>
