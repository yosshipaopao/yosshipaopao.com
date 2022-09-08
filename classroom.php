<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.5.1/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.5.1/highlight.min.js"></script>
<script>
  hljs.initHighlightingOnLoad();
</script>
<div class="blog">
  <h3>Classroom-notifaction</h3>
  <h3>仕様説明</h3>
  <a href="https://script.google.com/">
    <h3>GASの設定</h3>
  </a>
  <a href="https://script.google.com/u/2/home/projects/1FbIYa2lRpWz5h6byO0xJq7gskMMSE3FPGQMeMcMEDliEdt7-WIJNynIo/edit">
    <h3>GASのURL</h3>
  </a>
  <pre>
<code class="javascript">
//スプシの各種宣言
//スプシ:https://docs.google.com/spreadsheets/d/1BDKKRlsZuzZxQD49y6Kk3k_3S4H889Xq2Jx_EnM24Ok/edit
var sid = '1BDKKRlsZuzZxQD49y6Kk3k_3S4H889Xq2Jx_EnM24Ok';//スプレッドシートのid
var ss = SpreadsheetApp.openById(sid);
var sheet = ss.getSheetByName('worklist');

function doPost(e){
  //メールアドレスの取得
  var student = e.parameter.mail;
  //コース全取得
  var courses = listCourses();
  //json作成
  var joinList = {'titles':[],'ids':[]};
  
  //検出
  for (i = 0; i < courses[0].length; i++) {
    //生徒リストの検出
    var a = Classroom.Courses.Students.list(courses[0][i]).students;
    //生徒がいないとエラー
    if(a!=null){
      //someで検索
      if(a.some((u) => u['profile']['emailAddress'] == [student])){
        joinList['titles'].push(courses[1][i]);
        joinList['ids'].push(courses[0][i]);
      }
    }
  }
  //jsonに書き換え
  var payload = JSON.stringify(joinList);
  ContentService.createTextOutput();
  var output = ContentService.createTextOutput();
  output.setMimeType(ContentService.MimeType.JSON);
  output.setContent(payload);
  //return
  return output;
}

function listCourses() {
  //クラスのid,タイトル,urlを配列で送る
  //returunの内容:[[id],[タイトル],[url]]
  var courselist = [];
  var titles = [];
  var urls = [] ;
  //Apiからコースの全取得
  const courses = Classroom.Courses.list().courses;
  //配列に置き換え
  for (var course of courses) {
    courselist.push(course.id);
    titles.push(course.name);
    urls.push(course.alternateLink)
  }
  return [courselist,titles,urls];
}

function listCoursesWork(courseId) {
  //クラスidからid,タイトル,本文,url,投稿時(最終更新)
  //returnの内容:[[id],[タイトル],[本文],[url],[投稿時]]
  var worksList = [];
  var titles = [];
  var urls = [];
  var times = [];
  var descriptions = [];
  //Apiから全取得
  const course = Classroom.Courses.CourseWork.list(courseId).courseWork;
  //リストが空のときエラーが発生
  if(course!=null){
    //配列にpush
    for (let works of course) {
      let list = works.id;
      worksList.push(list);
      titles.push(works.title);
      urls.push(works.alternateLink);
      times.push(works.updateTime);
      descriptions.push(works.description);
    }
  }
  return [worksList, titles, descriptions, urls, times];
}

function check() {
  //更新を判断するプログラム
  
  //クラスが追加されたか
  //クラスの取得
  var courselist = listCourses();
  //スプレッドシートの最終列の取得
  let lastColumn = sheet.getLastColumn();
  //クラスとスプシの数から判断
  if(lastColumn < courselist[0].length){
    //スプレッドシートを書き直す
    sheet.clear();
    write_worklist();
    //クラスの追加時のプログラム↓
  }

  //課題の更新を判断
  for (i = 0; i < courselist[0].length; i++) {
    var classid = courselist[0][i];
    //課題を全取得
    var coursework = listCoursesWork(classid);
    //idの摘出
    var works = coursework[0];
    //最新のidの摘出
    var latest_work = works[0];
    //sheetの取得
    const data = sheet.getRange(1, i + 1, sheet.getLastRow()).getValues().flat(1);
    //最新のidが含まれていなかったとき
    if (!data.flat().includes(Number(latest_work))) {
      //課題の追加時の動作
      //最新の課題のinfo取得
      var titles = coursework[1];
      var descriptions = coursework[2];
      var urls = coursework[3];
      var times = coursework[4];
      //スプレッドシートの最終行取得
      var maxRow = sheet.getMaxRows();
      //最終行の読み込み
      var lastRow = sheet.getRange(maxRow, i + 1).getNextDataCell(SpreadsheetApp.Direction.UP).getRow();
      //Logger.log('変更がありました\n' + courselist[1][i] + '\nurl:' +courselist[2][i] + '\nid:' + latest_work + '\nタイトル:' + titles[0] + '\n本文:' + descriptions[0] + '\nurl:' + urls[0] + '\n投稿時間:' + times[0])
      //POSTするデータの生成
      var send_data = {'id': courselist[0][i],'class': courselist[1][i],'url': urls[0],'title': titles[0],'des': descriptions[0],'time': times[0]};
      //main.phpにPOST
      //sendHttpPost(send_data);
      /*----------
      ここに通知関数
      ----------*/
      notifaction(classid,titles[0],descriptions[0],urls[0]);
      //最終行に書き込み
      sheet.getRange(lastRow + 1, i + 1).setValue(latest_work)
    }
  }
}

function write_worklist() {
  //クラスのidの全取得
  var courselist = listCourses()[0];
  for (i = 0; i < courselist.length; i++) {
    //課題のid全取得
    var works = listCoursesWork(courselist[i])[0]
    //スプレッドシート用に配列を変換
    var values = [courselist[i]].concat(works)
    var tmp = [];
    for(var value of values) {
        tmp.push([value]);
    }
    //書き込み
    sheet.getRange(1, i + 1, tmp.length, 1).setValues(tmp)
  }
}

function notifaction(classid,title,des,url){
  //payloadはPOSTする内容
  var payload =
   {
     'classid': classid,
     'title': title,
     'des': des,
     'url': url
   };
   var options =
   {
     'method' : 'post',
     'payload' : payload
   };
   //test.phpにPOST
   var re = UrlFetchApp.fetch('https://classroom.yosshipaopao.com/notification.php', options);
}
</code>
</pre>
  <p> Classroom APIを利用します。 </p> <br/>
  <p> サービス ➜ ＋ ➜ Classroom </p> <br/>
  <a href="docs.google.com/spreadsheets">
    <h3>スプレッドシートの準備 </h3>
  </a>
  <p> 作成してシートの名前を<code>worklist</code>にする。 </p>
  <p> スプレッドシートid(https://docs.google.com/spreadsheets/d/<span class="red">1BDKKRlsZuzZxQD49y6Kk3k_3S4H889Xq2Jx_EnM24Ok</span>/edit)<br> </p> <br>
  <p> をGASの<br><code>//スプレッドシートid</code><br>の部分に書く </p>
  <h3> 実行テスト </h3>
  <p>GASで<code>write_worklist()</code>を実行</p>
  <p> <code>権限を許可</code>が出ますが読み取りしかしないので許可を押してください <br><br> スプレッドシートに数字が書き込まれたら成功 </p>
  <h3>サーバーの設定(通知の設定)</h3>
  <p>※classroom.yosshipaopao.comを利用する場合は無視してください</p>
  <a href="https://github.com/yosshipaopao/Classroom-notifaction">
    <p> Githubからコードをダウンロード </p>
  </a>
  <p> サーバーのインデックスに配置。 </p> <br>
  <h3 class="min-font">web-push-phpの設定</h3>
  <a href="https://github.com/web-push-libs/web-push-php">
    <p class="red">web-push-php</p>
  </a>
  <p>サーバーのコマンドを開き</p>
  <p><code>$ composer require minishlink/web-push</code>を実行</p>
  <p><code>SendPush.php</code>に<code>vendor</code>のパスを書く</p>
  <p><code>index.php</code>にWebアプリにデプロイしたGASにのURLを書く</p>
  <h3 class="min-font">鍵の設定</h3> <a href="https://web-push-codelab.glitch.me">https://web-push-codelab.glitch.me</a>
  <p>から<code>Public Key</code>,<code>Private key</code>をコピー</p>
  <p><code>public key</code>を<code>webpush.js</code>の<code>35:const appServerKey = '＜取得した公開鍵＞';</code>に入力</p>
  <p>また、<code>notifaction.php</code>の</p>
  <pre class="min-code"><code class="javascript">
11:const VAPID_SUBJECT = '＜ホームページのアドレス＞';
12:const PUBLIC_KEY = '＜取得した公開鍵＞';
13:const PRIVATE_KEY = '＜取得した秘密鍵＞';</code>
</pre>
  <p>に書き込み</p>
  <a href="https://firebase.google.com">
    <h3 class="min-font">Google Fire Base の設定</h3>
  </a>
  <p>ログイン後、</p>
  <p>プロジェクトの作成 → 画面通り設定 →<br><code>＜/＞</code>をクリック、→ 登録後出てきたコードを</p>
  <pre class="min-code">
      <code class="javascript">
  apiKey: "＜APIkey＞",
  authDomain: "＜プロジェクト名＞.firebaseapp.com",
  projectId: "＜プロジェクト名＞",
  storageBucket: "＜プロジェクト名＞.appspot.com",
  messagingSenderId: "＜数字＞",
  appId: "＜アプリID＞"
</code></pre>
  <p>の部分だけコピーして<code>config.js</code>に書き込み</p>
  <p>firebaseのログインプロバイダにGoogle、ドメインに自分のドメインを追加</p> <br><br>
  <p>多分これで完成</p>
  <p>参考:https://zenn.dev/nnahito/articles/fd2c8b0ad0d19a</p>
</div>