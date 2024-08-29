    </main>
        <footer>
            <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'menu_id'        => 'footer-menu',
                ));
            ?>
            
            <?php get_template_part('template-parts/content', "contact");?>
        </footer>

    <?php wp_footer(); ?>
</body>
</html>