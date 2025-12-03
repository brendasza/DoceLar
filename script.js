document.addEventListener("DOMContentLoaded", () => {
  initCarousel();
  document.getElementById("year").textContent = new Date().getFullYear();
});

/* ---------------- CAROUSEL ---------------- */
function initCarousel() {
  const slides = Array.from(document.querySelectorAll(".slide"));
  const indicatorsWrap = document.getElementById("carousel-indicators");
  if (!slides.length || !indicatorsWrap) return;

  let index = slides.findIndex(s => s.classList.contains("active"));
  if (index === -1) index = 0;

  // Cria os indicadores
  slides.forEach((_, i) => {
    const btn = document.createElement("button");
    if (i === index) btn.classList.add("active");
    btn.addEventListener("click", () => goTo(i));
    indicatorsWrap.appendChild(btn);
  });

  const prev = document.getElementById("carousel-prev");
  const next = document.getElementById("carousel-next");

  if (prev) prev.addEventListener("click", () => goTo(index - 1));
  if (next) next.addEventListener("click", () => goTo(index + 1));

  let timer = setInterval(() => goTo(index + 1), 5000);

  function goTo(newIndex) {
    clearInterval(timer);
    const n = slides.length;
    index = ((newIndex % n) + n) % n;
    slides.forEach((s, i) => s.classList.toggle("active", i === index));
    Array.from(indicatorsWrap.children).forEach((b, i) =>
      b.classList.toggle("active", i === index)
    );
    timer = setInterval(() => goTo(index + 1), 5000);
  }
}