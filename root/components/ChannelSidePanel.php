<div class='col-4'>
    <div class='p-3 bg-light'>
        <div class='mb-3'>
            <div class="d-flex">
                <form action="<?php if (!$is_subscribed) {echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=$user_id");} else {echo "";} ?>" method='post'>
                    <input type="hidden" name="subscribe" value=<?php echo $is_subscribed; ?>>
                    <button type='submit' class="btn <?php echo (!$is_subscribed) ? 'btn-danger' : 'btn-outline-danger'; ?>" <?php echo ($is_subscribed) ? 'disabled' : ''; ?>><?php echo (!$is_subscribed) ? 'Subscribe' : 'Subscribed'; ?></button>
                </form>
                <!-- <a href='#' class='btn btn-warning'>Add Contact</a> -->
            </div>
        </div>
        <!-- <h4>Messaging</h4>
        <div class='container bg-white mb-3' style='max-height:500px; overflow-y:scroll'>
        </div>
            <form action='' method='post'>
                <div class='form-group'>
                    <input type='text' class='form-control mb-3' placeholder='Message' name='message'>
                    <input type='submit' value='Send' class='btn btn-primary'>
                </div>
            </form>
        </div> -->
    </div>
</div>