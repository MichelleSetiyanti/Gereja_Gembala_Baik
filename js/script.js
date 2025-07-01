document.addEventListener("DOMContentLoaded", function () {
  let currentIndex = 0;
  const paginationItems = document.querySelectorAll(".pagination a");
  const backgroundImage = document.querySelector(".background-image");
  const contentTitle = document.querySelector(".content h1");
  const contentSubtitle = document.querySelector(".content h2");
  const contentText = document.querySelector(".content p");

  const pages = [
    {
      image: "../assets/bg_1.jpg",
      title: "JOIN THE PRAYER",
      subtitle: "Welcome to our church",
      text: "Charia Church is a Family of Faith committed to Bible teaching and joyful worship for all ages.",
    },
    {
      image: "../assets/bg_2.jpg",
      title: "EXPERIENCE THE FAITH",
      subtitle: "A Place of Love and Worship",
      text: "Join our community to strengthen your faith, share kindness, and experience peace in every prayer.",
    },
    {
      image: "../assets/bg_3.jpg",
      title: "FIND YOUR PURPOSE",
      subtitle: "Be Part of a Loving Community",
      text: "Discover your calling and walk in faith together with a family that prays, supports, and grows in grace.",
    },
  ];

  function updatePagination() {
    paginationItems.forEach((item, index) => {
      if (index === currentIndex) {
        item.classList.add("active");
        item.innerHTML = `- ${item.dataset.page} -`;
      } else {
        item.classList.remove("active");
        item.innerHTML = item.dataset.page;
      }
    });

    backgroundImage.src = pages[currentIndex].image;
    contentTitle.textContent = pages[currentIndex].title;
    contentSubtitle.textContent = pages[currentIndex].subtitle;
    contentText.textContent = pages[currentIndex].text;

    currentIndex = (currentIndex + 1) % pages.length;
  }

  setInterval(updatePagination, 5000);
  updatePagination();
});

document.addEventListener("DOMContentLoaded", function () {
  const navbar = document.querySelector(".navbar");

  window.addEventListener("scroll", function () {
    if (window.scrollY > 50) {
      navbar.classList.add("scrolled");
    } else {
      navbar.classList.remove("scrolled");
    }
  });
});
