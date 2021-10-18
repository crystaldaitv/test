<?php
defined('ABSPATH') || die;

get_header();
?>
    <div id="main-content">
        <div id="container" class="wrapper_content">
            <div id="content" >
                <main id="content-area">
                    <header class="page-header">
                        <h1 class="page-title">
                            <?php
                            esc_attr_e(
                                'Oops! That page can&rsquo;t be found.',
                                'advanced-gutenberg-theme'
                            ); ?>
                        </h1>
                    </header>
                    <div class="page-content">
                        <p>
                            <?php
                            esc_attr_e(
                                'It looks like nothing was found at this location.',
                                'advanced-gutenberg-theme'
                            ); ?>
                        </p>
                    </div>
                </main>
            </div>
            <div id="sidebar">
            </div>
        </div>
    </div>
<?php
get_footer();
