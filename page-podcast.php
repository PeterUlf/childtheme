<?php 
// Loads the header.php template.
get_header();
?>

<?php
// Dispay Loop Meta at top
hoot_display_loop_title_content( 'pre', 'page.php' );
if ( hoot_page_header_attop() ) {
	get_template_part( 'template-parts/loop-meta' ); // Loads the template-parts/loop-meta.php template to display Title Area with Meta Info (of the loop)
	hoot_display_loop_title_content( 'post', 'page.php' );
}

// Template modification Hook
do_action( 'hoot_template_before_content_grid', 'page.php' );
?>

<div class="hgrid main-content-grid">

    <?php
	// Template modification Hook
	do_action( 'hoot_template_before_main', 'page.php' );
	?>

    <!--   Her starter min kode-->
    <template>
        <article>
            <img src="" alt="">
            <div>
                <h2></h2>
                <p class="beskrivelse"></p>
            </div>
        </article>
    </template>

    <section id="primary" class="content-area">
        <main id="main" class="site-main">
            <nav id="filtrering"><button data-podcast="alle">Alle</button></nav>
            <section id="podcastcontainer">
            </section>
        </main><!-- #main -->

        <script>
            let podcasts;
            let categories;
            let filterPodcast = "alle";
            const dbUrl = "https://tema9.peujatkea.dk/wp-json/wp/v2/podcasts?per_page=100";
            const catUrl = "https://tema9.peujatkea.dk/wp-json/wp/v2/categories";


            async function getJson() {
                const data = await fetch(dbUrl);
                const catdata = await fetch(catUrl);
                podcasts = await data.json();
                categories = await catdata.json();
                console.log(categories);
                visPodcasts();
                opretknapper();
            }

            function opretknapper() {

                categories.forEach(cat => {
                    document.querySelector("#filtrering").innerHTML += `<button class="filter" data-podcast="${cat.id}">${cat.name}</button>`
                })

                addEventListenersToButtons();
            }

            function addEventListenersToButtons() {
                document.querySelectorAll("#filtrering button").forEach(elm => {
                    elm.addEventListener("click", filtrering);
                })

            };

            function filtrering() {
                filterPodcast = this.dataset.podcast;
                console.log(filterPodcast);

                visPodcasts();
            }

            function visPodcasts() {
                let temp = document.querySelector("template");
                let container = document.querySelector("#podcastcontainer")
                container.innerHTML = "";
                podcasts.forEach(podcast => {
                    if (filterPodcast == "alle" || podcast.categories.includes(parseInt(filterPodcast))) {

                        let klon = temp.cloneNode(true).content;
                        klon.querySelector("h2").innerHTML = podcast.title.rendered;
                        //                        klon.querySelector("img").src = ret.billede.guid;
                        klon.querySelector(".beskrivelse").innerHTML = podcast.content.rendered;
                        //                        klon.querySelector(".pris").textContent = ret.pris;
                        klon.querySelector("article").addEventListener("click", () => {
                            location.href = podcast.link;
                        })
                        container.appendChild(klon);
                    }
                })

            }

            getJson();

        </script>

    </section><!-- #primary -->





    <!--her slutter min kode-->

    <?php
	// Template modification Hook
	do_action( 'hoot_template_after_main', 'page.php' );
	?>

    <?php hybridextend_get_sidebar(); // Loads the sidebar.php template. ?>

</div><!-- .hgrid -->

<?php get_footer(); // Loads the footer.php template. ?>
