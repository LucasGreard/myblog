<div class="col-lg-8">
    <!-- Blog post-->
    <div class="card mb-4 col-lg-12">
        <a href="#!">
            <img class="card-img-top" src="https://dummyimage.com/700x350/dee2e6/6c757d.jpg" alt="..." />
        </a>
        <div class="card-body">
            <div class="small text-muted">' . $data['post_Date_Modif'] . '</div>
            <h2 class="card-title h4"><a class="card-title h4" href="index.php?action=listComment&id=' . $data['id'] . '">' . $data['post_Heading'] . '</a></h2>
            <p class="card-text">' . $data['post_Content'] . '</p>
            <a class="btn btn-light" href="index.php?action=listComment&id=' . $data['id'] . '">Read more →</a>
        </div>
    </div>
</div>
</div>
<div class="row">



    <div class="col-lg-6">
        <!-- Blog post-->
        <div class="card mb-4">
            <a href="#!">
                <img class="card-img-top" src="https://dummyimage.com/700x350/dee2e6/6c757d.jpg" alt="..." />
            </a>
            <div class="card-body">
                <div class="small text-muted">' . $data['post_Date_Add'] . '</div>
                <h2 class="card-title h4"><a class="card-title h4" href="index.php?action=listComment&id=' . $data['id'] . '">' . $data['post_Heading'] . '</a></h2>
                <p class="card-text">' . $data['post_Content'] . '</p>
                <a class="btn btn-light" href="index.php?action=listComment&id=' . $data['id'] . '">Read more →</a>
            </div>
        </div>
    </div>