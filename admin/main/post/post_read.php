<style>
    /* Custom CSS for Newspaper Read Mode */

    /* General Container Styling */
    .container {
        background-color: #fff;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Page Title Styling */
    .pagetitle {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    /* Subtitle Styling */
    .subtitle {
        font-size: 1.2rem;
        font-weight: 500;
        color: #555;
        text-align: center;
        margin-bottom: 30px;
    }

    /* Newspaper Column Layout */
    .content-columns {
        column-count: 2;
        column-gap: 40px;
        line-height: 1.6;
        font-size: 1.1rem;
        color: #444;
    }

    /* Image Styling */
    .content-image {
        display: block;
        max-width: 100%;
        height: auto;
        margin: 20px auto;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Excerpt Styling */
    .excerpt {
        font-style: italic;
        margin-bottom: 30px;
        font-size: 1.2rem;
        color: #666;
        text-align: justify;
    }

    /* Article Meta Info */
    .meta-info {
        font-size: 0.9rem;
        color: #999;
        text-align: right;
        margin-bottom: 10px;
    }

    /* Hide Sidebar in Read Mode */
    .sidebar {
        display: none;
    }

    /* Clear Floats */
    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }
</style>

<div id="mainpage" class="container">
    <!-- Page Title -->
    <div class="pagetitle">
        Architecture
    </div>
    <!-- Subtitle -->
    <div class="subtitle">
        This is the sub
    </div>

    <!-- Main Content Area -->
    <div class="content-columns">
        <!-- Excerpt -->
        <div class="excerpt">
            This is the excerpt
        </div>

        <!-- Image -->
        <img src="https://vivalibro.com/media/40027562_10155955799054315_3272421117096099840_n.jpg" alt="Architecture Image" class="content-image">

        <!-- Article Content -->
        <p>
            Gaia's purpose...
            <!-- Insert more content here to fill the newspaper layout -->
        </p>

        <!-- Article Meta Information -->
        <div class="meta-info">
            Created: 2017-01-03 17:23 <br>
            Modified: 2019-06-04 13:43 <br>
            Published: 2017-01-03 17:23
        </div>
    </div>
</div>
