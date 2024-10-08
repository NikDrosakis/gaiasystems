const Main=()=> {
  return (
    <div id="main">
      <div className="container">
        <div className="row main-row">
          <div className="col-4 col-12-medium">
            <section>
              <h2>Welcome to Poetry!</h2>
              <p><a href="http://dev.nikosdrosakis.gr">NikDrosakis</a>. Lightweight design, solid HTML5 and CSS3 code, and full responsive support for desktop,
                tablet, and small displays.</p>
              <footer className="controls">
                <a href="http://dev.nikosdrosakis.gr">NikDrosakis</a>
              </footer>
            </section>
          </div>
          <div className="col-4 col-6-medium col-12-small">
            <section>
              <h2>Who are you guys?</h2>
              <ul className="small-image-list">
                <li>
                  <a href="#"><img src="images/pic2.jpg" alt="" className="left" /></a>
                  <h4>Jane Anderson</h4>
                  <p>Varius nibh. Suspendisse vitae magna eget et amet mollis justo facilisis amet quis.</p>
                </li>
                <li>
                  <a href="#"><img src="images/pic1.jpg" alt="" className="left" /></a>
                  <h4>James Doe</h4>
                  <p>Vitae magna eget odio amet mollis justo facilisis amet quis. Sed sagittis consequat.</p>
                </li>
              </ul>
            </section>
          </div>
          <div className="col-4 col-6-medium col-12-small">
            <section>
              <h2>How about some links?</h2>
              <div>
                <div className="row">
                  <div className="col-6 col-12-small">
                    <ul className="link-list">
                      <li><a href="#">Sed neque nisi consequat</a></li>
                      <li><a href="#">Dapibus sed mattis blandit</a></li>
                      <li><a href="#">Quis accumsan lorem</a></li>
                      <li><a href="#">Suspendisse varius ipsum</a></li>
                      <li><a href="#">Eget et amet consequat</a></li>
                    </ul>
                  </div>
                  <div className="col-6 col-12-small">
                    <ul className="link-list">
                      <li><a href="#">Quis accumsan lorem</a></li>
                      <li><a href="#">Sed neque nisi consequat</a></li>
                      <li><a href="#">Eget et amet consequat</a></li>
                      <li><a href="#">Dapibus sed mattis blandit</a></li>
                      <li><a href="#">Vitae magna sed dolore</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </section>

          </div>
          <div className="col-6 col-12-medium">
            <section>
              <h2>An assortment of pictures and text</h2>
              <p>Duis neque nisi, dapibus sed mattis quis, rutrum et accumsan.
                Suspendisse nibh. Suspendisse vitae magna eget odio amet mollis
                justo facilisis quis. Sed sagittis mauris amet tellus gravida
                lorem ipsum dolor sit amet consequat blandit lorem ipsum dolor
                sit amet consequat sed dolore.</p>
              <ul className="big-image-list">
                <li>
                  <a href="#"><img src="images/pic3.jpg" alt="" className="left" /></a>
                  <h3>Magna Gravida Dolore</h3>
                  <p>Varius nibh. Suspendisse vitae magna eget et amet mollis justo
                    facilisis amet quis consectetur in, sollicitudin vitae justo. Cras
                    Maecenas eu arcu purus, phasellus fermentum elit.</p>
                </li>
                <li>
                  <a href="#"><img src="images/pic4.jpg" alt="" className="left" /></a>
                  <h3>Magna Gravida Dolore</h3>
                  <p>Varius nibh. Suspendisse vitae magna eget et amet mollis justo
                    facilisis amet quis consectetur in, sollicitudin vitae justo. Cras
                    Maecenas eu arcu purus, phasellus fermentum elit.</p>
                </li>
                <li>
                  <a href="#"><img src="images/pic5.jpg" alt="" className="left" /></a>
                  <h3>Magna Gravida Dolore</h3>
                  <p>Varius nibh. Suspendisse vitae magna eget et amet mollis justo
                    facilisis amet quis consectetur in, sollicitudin vitae justo. Cras
                    Maecenas eu arcu purus, phasellus fermentum elit.</p>
                </li>
              </ul>
            </section>
          </div>
          <div className="col-6 col-12-medium">
            <article className="blog-post">
              <h2>Just another blog post</h2>
              <a className="comments" href="#">33 comments</a>
              <a href="#"><img src="images/pic6.jpg" alt="" className="top blog-post-image" /></a>
              <h3>Magna Gravida Dolore</h3>
              <p>Aenean non massa sapien. In hac habitasse platea dictumst.
                Maecenas sodales purus et nulla sodales aliquam. Aenean ac
                porttitor metus. In hac habitasse platea dictumst. Phasellus
                blandit turpis in leo scelerisque mollis. Nulla venenatis
                ipsum nec est porta rhoncus. Mauris sodales sed pharetra
                nisi nec consectetur. Cras elit magna, hendrerit nec
                consectetur in, sollicitudin vitae justo. Cras amet aliquet
                Aliquam ligula turpis, feugiat id fermentum malesuada,
                rutrum eget turpis. Mauris sodales sed pharetra nisi nec
                consectetur. Cras elit magna, hendrerit nec consectetur
                in sollicitudin vitae.</p>
              <footer className="controls">
                <a href="#" className="button">Continue Reading</a>
              </footer>
            </article>
          </div>
        </div>
      </div>
    </div>
  )
}
export default Main;