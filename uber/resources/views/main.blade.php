<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uber</title>

<style>
  *,
*::after,
*::before {
  margin: 0;
  padding: 0;
  box-sizing: inherit;
  font-family: inherit;
  transition: all 0.3s ease-in-out;
}
html {
  font-size: 100%;
  scroll-behavior: smooth;
  overscroll-behavior: none;
}
body {
  overscroll-behavior: none;
  box-sizing: border-box;
  position: relative;
  line-height: 1.5;
  font-family: sans-serif;
  overflow-x: hidden;
  transition: all 0.3s ease-in-out;
}
.heading-primary {
  font-size: 6rem;
  text-transform: uppercase;
  letter-spacing: 3px;
  text-align: center;
  font-weight: 800;
}
.heading-sec_main {
  display: block;
  font-size: 4rem;
  text-transform: uppercase;
  letter-spacing: 1px;
  letter-spacing: 3px;
  text-align: center;
  margin-bottom: 3.5rem;
  position: relative;
}
.heading-sec_main--lt {
  font-weight: 800;
}
.heading-sec_main--lt::after {
  content: "";
  background: #7843e9 !important;
}
.heading-sec_main::after {
  content: "";
  position: absolute;
  top: calc(100% + 1.5rem);
  height: 5px;
  width: 3rem;
  background: #7843e9;
  left: 50%;
  transform: translateX(-50%);
  border-radius: 5px;
}
.heading-sec_sub {
  display: block;
  text-align: center;
  font-size: 2rem;
  font-weight: 500;
  max-width: 80rem;
  margin: auto;
  line-height: 1.6;
}
.heading-sm {
  font-size: 2.2rem;
  text-transform: uppercase;
  letter-spacing: 1px;
}

</style>

</head>
<body>
    <header id="header" class="header">
      <div class="header_content">
        <div class="header_logo-container">
          <div class="header_logo-img-cont">
            <img 
              src="/resources/assets/svg/uber-logo.svg"
              alt="Picture Sefer Tasdemir"
              class="header_logo-img"
            />
          </div>
          <span class="header_logo-sub">Sefer Tasdemir</span>
        </div>
        <div class="header_main">
          <ul class="header_links">
            <li class="header_link-wrapper">
              <a href="#home" class="header_link" data-i18n="header.home"></a>
            </li>
            <li class="header_link-wrapper">
              <a href="#about" class="header_link" data-i18n="header.about"></a>
            </li>
            <li class="header_link-wrapper">
              <a
                href="#projects"
                class="header_link"
                data-i18n="header.projects"
              ></a>
            </li>
            <li class="header_link-wrapper">
              <a
                href="#contact"
                class="header_link"
                data-i18n="header.contact"
              ></a>
            </li>
            <li class="header_link-wrapper">
              <a href="#CV" class="header_link">CV</a>
            </li>
          </ul>
        </div>
        <div class="header_main-ham-menu-cont">
          <img
            src="/resources/assets/svg/uber-logo.svg"
            alt="hamburger menu"
            class="header_main-ham-menu"
          />
          <img
            src="assets\svg\ham-menu-close.svg"
            alt="hamburger menu close"
            class="header_main-ham-menu-close d-none"
          />
        </div>
      </div>
      <div class="header_sm-menu">
        <div class="header_sm-menu-cont">
          <ul class="header_sm-menu-links">
            <li class="header_sm-menu-link">
              <a href="#home" data-i18n="header.home"></a>
            </li>
            <li class="header_sm-menu-link">
              <a href="#about" data-i18n="header.about"></a>
            </li>
            <li class="header_sm-menu-link">
              <a href="#projects" data-i18n="header.projects"></a>
            </li>
            <li class="header_sm-menu-link">
              <a href="#contact" data-i18n="header.contact"></a>
            </li>
            <li class="header_sm-menu-link">
              <a href="#CV">CV</a>
            </li>
          </ul>
        </div>
      </div>
    </header>

</body>
</html>