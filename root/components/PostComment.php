<!-- appending the media id query to the url so it is supplied alongside "post" -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=$media_id");?>" method="post" autocomplete="off">
    <div class="form-group">
        <textarea type="text" 
        class="form-control" 
        name="comment"
        id="commentTextArea"
        rows="1"
        placeholder="Leave a comment."
        required="true"></textarea>
        <span class="text-danger"></span>
        <input type="hidden"
        name="target"
        id="commentTarget"
        value="">
    </div>
    <button type="submit" class="btn btn-outline-warning" id="submit">Comment</button>
</form>