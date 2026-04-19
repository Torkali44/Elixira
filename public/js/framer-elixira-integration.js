/**
 * Wires Laravel routes & featured products into the exported Framer homepage (unchanged markup).
 */
(function () {
    const E = window.Elixira || {};

    function go(url) {
        if (url) window.location.href = url;
    }

    function patchProductCards() {
        const items = E.featured || [];
        if (!items.length) return;

        const links = Array.from(document.querySelectorAll("a.framer-hc6psi"));
        links.forEach((a, i) => {
            const item = items[i];
            if (!item) return;

            a.setAttribute("href", item.url);

            const nameEl = a.querySelector('[data-framer-name="PrdctName"] p');
            if (nameEl) nameEl.textContent = item.name;

            const descEl = a.querySelector('[data-framer-name="CardDiscription"] p');
            if (descEl && item.description) descEl.textContent = item.description;

            const catEl = a.querySelector(
                '[data-framer-name="Variant 1"] [data-framer-name="Txt"] p'
            );
            if (catEl && item.category) catEl.textContent = item.category;

            const pricePs = a.querySelectorAll(".framer-text");
            pricePs.forEach((p) => {
                const t = (p.textContent || "").trim();
                if (/^\d+(\.\d+)?$/.test(t)) {
                    p.textContent = String(Math.round(item.price * 100) / 100);
                }
            });

            const heroImg = a.querySelector(".framer-1ucj02d img");
            if (heroImg && item.image) {
                heroImg.src = item.image;
                heroImg.removeAttribute("srcset");
            }
        });
    }

    function patchCartUi() {
        const cartUrl = E.routes && E.routes.cart;
        const loginUrl = E.routes && E.routes.login;
        const registerUrl = E.routes && E.routes.register;

        const cartBtn = document.querySelector(
            '[data-framer-name="Cart Button"] button'
        );
        if (cartBtn && cartUrl) {
            cartBtn.addEventListener("click", function (e) {
                e.preventDefault();
                go(cartUrl);
            });
        }

        document.querySelectorAll("p.framer-text, span.framer-text").forEach((el) => {
            const t = (el.textContent || "").trim();
            if (t === "Go Cart" && cartUrl) {
                const wrap = el.closest("a") || el.closest("[data-framer-name]");
                if (wrap) {
                    wrap.style.cursor = "pointer";
                    wrap.addEventListener("click", function (e) {
                        e.preventDefault();
                        go(cartUrl);
                    });
                }
            }
        });

        document.querySelectorAll("p.framer-text").forEach((el) => {
            if ((el.textContent || "").trim() !== "Join Us") return;
            const row = el.closest(".framer-zv3a0h");
            if (row && registerUrl) {
                row.style.cursor = "pointer";
                row.addEventListener("click", function () {
                    go(registerUrl);
                });
            }
        });

        const count = typeof E.cartCount === "number" ? E.cartCount : 0;
        const badgeHost = document.querySelector(
            '[data-framer-name="Number of Cart Items"] [data-code-component-plugin-id]'
        );
        if (badgeHost && count > 0) {
            badgeHost.textContent = String(count);
            badgeHost.style.cssText +=
                ";display:flex;align-items:center;justify-content:center;min-width:20px;font-size:12px;font-weight:600;color:#111;";
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        patchProductCards();
        patchCartUi();
    });
})();
