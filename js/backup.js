var elem = document.getElementById("mainstyle"),
 storage = sessionStorage,
 val = storage.getItem("style");

function sleep(t) {
 return new Promise(function (e, s) {
  window.setTimeout(e, t)
 })
}
elem.href = 0 == val ? "css/new.css" : 1 == val ? "css/test.css" : "css/style.css", console.log(val);
async function changeStyleSheet() {
 var e = document.getElementById("loading"),
  s = document.getElementById("mainstyle"),
  t = sessionStorage;
 e.classList.remove("loaded"), "https://yosshipaopao.com/css/style.css" == s.href ||"https://yosshipaopao.work/css/style.css" == s.href ? (s.href = "css/test.css", t.setItem("style", 1)) : "https://yosshipaopao.com/css/test.css" == s.href || "https://yosshipaopao.work/css/test.css" == s.href ? (s.href = "css/new.css", t.setItem("style", 0)) : (s.href = "css/style.css", t.setItem("style", 2)), await sleep(1e3), e.classList.add("loaded")
}
var isset = function (e) {
 return "" !== e && null != e
};
window.addEventListener("load", function () {
 var e = document.createElement("div"),
  s = document.createElement("div");
 e.id = "cursor", s.id = "follower", document.body.appendChild(e), document.body.appendChild(s);
 const t = document.getElementById("loading");
 isset(t) && t.classList.add("loaded");
 const o = document.getElementById("nyancat");
 isset(o) && o.classList.add("loaded"), $(function () {
  var e = $("#cursor"),
   s = $("#follower"),
   t = 0,
   o = 0,
   a = 0,
   n = 0;
  TweenMax.to({}, .001, {
   repeat: -1,
   onRepeat: function () {
    a += (t - a) / 10, n += (o - n) / 10, TweenMax.set(s, {
     css: {
      left: a - 20,
      top: n - 20
     }
    }), TweenMax.set(e, {
     css: {
      left: t - 4,
      top: o - 4
     }
    })
   }
  }), $(document).on("mousemove", function (e) {
   t = e.pageX, o = e.pageY
  }), $("body").on({
   mouseenter: function () {
    e.removeClass("mouseleave"), s.removeClass("mouseleave")
   },
   mouseleave: function () {
    e.addClass("mouseleave"), s.addClass("mouseleave")
   }
  }), $("iframe").on({
   mouseenter: function () {
    e.addClass("mouseleave"), s.addClass("mouseleave")
   },
   mouseleave: function () {
    e.removeClass("mouseleave"), s.removeClass("mouseleave")
   }
  }), $("a").on({
   mouseenter: function () {
    e.addClass("active"), s.addClass("active")
   },
   mouseleave: function () {
    e.removeClass("active"), s.removeClass("active")
   }
  })
 })
});