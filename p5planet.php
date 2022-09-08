<!DOCTYPE html>
<html lang="en">
  <head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.4.1/p5.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.4.1/addons/p5.sound.min.js"></script>
    <style>html, body {
  margin: 0;
  padding: 0;
}
canvas {
  display: block;
}
</style>
    <meta charset="utf-8" />

  </head>
  <body>
    <main>
    </main>
    <script>var cam_size = 1;
let pl_size = 1;
//基準の作成;基準は地球
const planet_radius = 4; //半径(px)惑星のサイズ
const orbital_radius = 60; //公転半径(px)実質カメラサイズ
const orbital_period = 8; //公転周期(s)実質速度
const text_size = 20; //テキストのサイズ
//天体の情報
let sun_name = "sun";
let planet = {
  //"天体の名前" : [天体の半径(地球からの相対)、公転半径(地球からの相対)、公転周期(日数),[R,G.B]]
  mercury: [0.38, 0.4, 87, [0, 255, 255]],
  venus: [0.95, 0.7, 224.7, [255, 255, 0]],
  earth: [1, 1, 365, [0, 100, 255]],
  mars: [0.53, 1.5, 687, [255, 100, 0]],
  jupiter: [11.21, 5.2, 4329, [150, 150, 150]],
  saturn: [9.45, 9.6, 10752, [128, 128, 0]],
  uranus: [4.01, 19.2, 60142, [50, 200, 200]],
  neptune: [3.88, 30, 90445, [0, 0, 255]],
};
//天体オブジェクト(new Star)保存用
let stars = {};
//time?
let t = 0;
//setup
//Canvas作成とnew Starオブジェクトの設定
function setup() {
  createCanvas(windowWidth, windowHeight);
  //new Star(天体の半径(px)、公転半径(px)、公転周期(s)、天体の色、天体の名前)
  Object.keys(planet).forEach(function (key) {
    //連想配列からforEachでkeyの取得
    //stars[天体名]にnew Starオブジェクトを代入
    //このとき相対から絶対に変換
    stars[key] = new Star(
      planet_radius * planet[key][0],
      orbital_radius * planet[key][1],
      (orbital_period * planet[key][2]) / 365,
      color(planet[key][3]),
      key
    );
  });

  sl_pl_ra = createSlider(0, 99, 81);
  sl_pl_ra.position(100, windowHeight - 20);
  sl_pl_ra.style("width", windowWidth - 150 + "px");
  sl_ob_ra = createSlider(0, 99, 81);
  sl_ob_ra.position(100, windowHeight - 40);
  sl_ob_ra.style("width", windowWidth - 150 + "px");
}
//画面更新
function draw() {
  //画面の更新
  base();
  pl_size = 10 - Math.sqrt(sl_pl_ra.value());
  cam_size = 10 - Math.sqrt(sl_ob_ra.value());
  text("半径", 50, windowHeight - 10);
  text("画面サイズ", 30, windowHeight - 25);
  //天体の描画
  Object.keys(stars).forEach(function (key) {
    //starsからforEachでkeyを取得
    //stars[key]はnew Starオブジェクト
    //_draw()で描画
    stars[key]._draw();
  });
}

function base() {
  //時間を追加
  t++;
  //画面を更新
  background(0);
  //Sunの色設定?
  fill(255, 100, 0);
  //円(Sun)の描画
  ellipse(width / 2, height / 2, planet_radius * 5, planet_radius * 5);
  //テキスト(Sun)の色
  fill(255);
  //テキスト(Sun)の描画
  text(sun_name, width / 2, height / 2);
}
//classの設定 関数の進化版みたいな?
class Star {
  constructor(sr, r, p, clr, n) {
    this.posx = 0;
    this.posy = 0;
    //順番揃えようよ...
    this.name = n;
    //usを失ったRadius達
    this.starRadi = sr;
    this.radi = r;
    this.period = p;
    this.starColor = clr;
  }
  _draw() {
    //ここわからん
    noFill();
    smooth();
    stroke(255);
    ellipse(
      width / 2,
      height / 2,
      this.radi * 2 * cam_size,
      this.radi * 2 * cam_size
    );
    fill(this.starColor);
    noStroke();
    ellipse(
      this.posx,
      this.posy,
      this.starRadi * 2 * pl_size,
      this.starRadi * 2 * pl_size
    );
    fill(255);
    this.posx =
      this.radi * cam_size * cos((2 * PI * (t / 60)) / this.period) + width / 2;
    this.posy =
      this.radi * cam_size * sin((2 * PI * (t / 60)) / this.period) +
      height / 2;
    let nx =
      (this.radi * cam_size + 20) * cos((2 * PI * (t / 60)) / this.period) +
      width / 2;
    let ny =
      (this.radi * cam_size + 20) * sin((2 * PI * (t / 60)) / this.period) +
      height / 2;
    textAlign(CENTER, CENTER);
    textSize(text_size);
    text(this.name, nx, ny);
  }
}
</script>
  </body>
</html>
