<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="container index">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="jumbotron text-white">
                <h1 class="display-4"><?php echo htmlspecialchars( $title, ENT_COMPAT, 'UTF-8', FALSE ); ?></h1>
                <p class="lead"><?php echo htmlspecialchars( $subtitle, ENT_COMPAT, 'UTF-8', FALSE ); ?></p>
                <hr class="my-4">
                <div class="row">
                    <div class="col-lg-2">
                        <img src="https://avatars.githubusercontent.com/u/36117649?v=4" alt="Victor Alves Mendes" class="profile">
                    </div>
                    <div class="col-lg-10">
                        <p><strong><?php echo htmlspecialchars( $content, ENT_COMPAT, 'UTF-8', FALSE ); ?></strong></p>
                        <a href="https://github.com/devvime" target="_blank"><img src="@vime/images/github-light.png" class="github"></a>
                        <a href="https://www.instagram.com/viimee" target="_blank"><img src="@vime/images/instagram-light.png" class="insta"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>