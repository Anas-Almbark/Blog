document.addEventListener("DOMContentLoaded", function () {
  let blogs = [];
  let blogIndex = 0;

  // Fetch blogs from the server
  fetch("http://localhost/Blog/back-end/blog.php?getBlogs=true")
    .then((response) => response.json())
    .then((data) => {
      let hero_data = data[Math.floor(Math.random() * data.length)];
      document.getElementById(
        "hero_link"
      ).href = `./single.html?id=${hero_data.id}`;
      document.getElementById("hero_img").src = `../${hero_data.img}`;
      document.getElementById("hero_title").innerHTML = hero_data.title;
      document.getElementById("hero_content").innerHTML =
        hero_data.content.substring(0, 50) + "...";

      blogs = data;
      loadMoreBlogs(4);
    })
    .catch((error) => console.error("Error fetching blogs:", error));

  function* blogGenerator() {
    while (blogIndex < blogs.length) {
      yield blogs[blogIndex++];
    }
  }

  const blogGen = blogGenerator();

  function loadMoreBlogs(count) {
    const container = document.getElementById("box-blogs");
    for (let i = 0; i < count; i++) {
      const blog = blogGen.next();
      if (blog.done) {
        document.getElementById("loadMoreBtn").style.display = "none";
        break;
      }
      const card = document.createElement("div");
      card.className = "col-xl-6 col-lg-12 text-center";
      const contentPreview =
        blog.value.content.length > 50
          ? blog.value.content.substring(0, 25) + "..."
          : blog.value.content;
      card.innerHTML = `
              <a href="./single.html?id=${blog.value.id}">
                  <div class="article-card d-flex justify-content-center align-items-center">
                      <div class="article-meta">
                          <img src="../${blog.value.img}" class="rounded-circle" width="150" height="150"/>
                      </div>
                      <div class="article-meta text-left">
                          <h2>${blog.value.title}</h2>
                          <p>${contentPreview}</p>
                      </div>
                  </div>
              </a>
          `;
      container.appendChild(card);
    }
  }

  document
    .getElementById("loadMoreBtn")
    .addEventListener("click", function (e) {
      e.preventDefault();
      loadMoreBlogs(4);
    });
});
