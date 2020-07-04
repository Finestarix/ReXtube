<style>
    #upload-video, #upload-thumbnail {
        cursor: pointer;

        width: 100%;
        height: 100px;

        border: 2px dashed #d1d1d1;

        text-align: center;
        line-height: 100px;
        font-size: 20px;
    }
</style>

<div class="m-3 bg-light">

    <form action="/controller/uploadController.php"
          method="post"
          enctype="multipart/form-data">

        <div style="text-align: center;"
             class="h3">
            Upload Video
        </div>

        <div class="form-group">
            <label for="title"><b>Title</b></label>
            <input type="text"
                   style="outline: none; box-shadow: none !important;"
                   class="form-control bg-light"
                   id="title"
                   name="title"
                   placeholder="Enter Title">
        </div>

        <div class="form-group">
            <label><b>Choose Video</b></label>
            <div id="upload-video"
                 ondrop="showVideoData(event)"
                 ondragover="allowDropData(event)"
                 class="mt-2 mb-2">
                <i class="fa fa-upload"></i> Drag Video Here
            </div>
            <input type="file"
                   id="upload-video-data"
                   name="video"
                   class="mt-2 mb-2">
        </div>

        <div class="form-group">
            <label><b>Choose Thumbnail</b></label>
            <div id="upload-thumbnail"
                 ondrop="showThumbnailData(event)"
                 ondragover="allowDropData(event)"
                 class="mt-2 mb-2">
                <i class="fa fa-upload"></i> Drag Thumbnail Here
            </div>
            <input type="file"
                   id="upload-thumbnail-data"
                   name="thumbnail"
                   class="mt-2 mb-2">
        </div>

        <div class="form-group">
            <label for="description"><b>Description</b></label>
            <textarea id="description"
                      name="description">
            </textarea>
        </div>

        <?php
        if (isset($_SESSION['ERROR'])) {
            ?>
            <div class="alert alert-danger" role="alert">
                <?= $_SESSION['ERROR'] ?>
            </div>
            <?php
            unset($_SESSION['ERROR']);
        }
        ?>

        <button type="submit"
                class="btn btn-secondary w-100">
            Insert
        </button>

    </form>

</div>

<script>
    CKEDITOR.replace('description');

    const fileVideoElement = document.getElementById('upload-video-data');
    const fileThumbnailElement = document.getElementById('upload-thumbnail-data');

    function allowDropData(e) {
        e.preventDefault();
    }

    function showVideoData(e) {
        e.preventDefault();
        fileVideoElement.files = e.dataTransfer.files;
    }

    function showThumbnailData(e) {
        e.preventDefault();
        fileThumbnailElement.files = e.dataTransfer.files;
    }

</script>