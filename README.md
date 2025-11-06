<p align="center">
      <img src="https://img.shields.io/badge/-Laravel-black.svg?logo=laravel&style=plastic"> <img src="https://img.shields.io/badge/-Html5-pink.svg?logo=html5&style=plastic"> <img src="https://img.shields.io/badge/-CSS3-blue.svg?logo=css3&style=plastic"> <img src="https://img.shields.io/badge/-Php-orange.svg?logo=php&style=plastic"> <img src="https://img.shields.io/badge/-Mysql-green.svg?logo=mysql&style=plastic"> <img src="https://img.shields.io/badge/-Windows-0078D6.svg?logo=windows&style=plastic"> <img src="https://img.shields.io/badge/-Docker-1488C6.svg?logo=docker&style=plastic"> <img src="https://img.shields.io/badge/-Nginx-red.svg?logo=nginx&style=plastic"> <img src="https://img.shields.io/badge/-Github-181717.svg?logo=github&style=plastic">
</p>

<h3 align="center">Atte（アット）勤怠管理システムアプリ</h3>

<p align="center">
    <img src="atte_register.png" alt="ユーザー登録画面">
</p>
<p align="center">
    <img src="atte_login.png" alt="ユーザーログイン画面">
</p>
<p align="center">
    <img src="atte_stamp.png" alt="打刻ページ画面">
</p>
<p align="center">
    <img src="atte_user-list.png" alt="勤怠表画面">
</p>

### サービス概要

Atte は、企業の勤怠管理システムです。ユーザーは勤務の開始と終了を記録し、休憩時間の管理も行うことができます。日付別やユーザーごとに勤怠データを取得し、表示する機能も備えています。

### 制作の背景と目的

このシステムは、企業内での人事評価の精度を向上させるために開発されました。正確な勤務記録に基づいて、従業員の評価を行うことが可能になります。

### 制作の目標

- 利用者数 100 人を達成すること。
- ユーザーが直感的に操作できるシンプルなインターフェースを提供する。
- 安全かつ正確に勤怠データを管理し、企業の人事評価に貢献する。

### 主な機能一覧

- **勤務記録の管理**: ユーザーは勤務の開始と終了を記録できます。日を跨ぐ勤務にも対応しています。
- **休憩時間の管理**: 休憩の開始と終了を記録し、管理することが可能です。
- **勤怠データの取得と表示**: 日付別、ユーザーごとに勤怠記録を取得し、表示することができます。ユーザー一覧も表示可能です。
- **セキュリティとユーザー認証**: メールによる本人確認を提供し、セキュリティを強化しています。

### 登録プロセス

- ユーザはメールアドレスと強力なパスワードを使用してアカウントを作成します。
- パスワードは８文字以上少なくとも大文字一つ以上含まれる必要があります。

### データモデル

- **User テーブルと AttendanceRecords テーブル:** Users テーブルの `id` と AttendanceRecords テーブルの `user_id` を外部キーで関連付けることにより、1 対多の関係を表現します。これにより、一人のユーザーが複数の勤務記録を持つことができます。

- **AttendanceRecords テーブルと BreakTime テーブル**: AttendanceRecords テーブルの `id` と BreakTime テーブルの `attendance_record_id` を外部キーで関連付けることで、1 対多の関係を形成します。これにより、一つの勤務記録に対して複数の休憩時間が記録されることが可能になります。

### セキュリティ

- パスワードは８文字以上で少なくとも一つの大文字が必要です。

### 使用技術（実行環境）

- **開発言語**: PHP
- **フレームワーク**: Laravel 8.x
- **データベース**: MySQL
- **バージョン管理**: GitHub
- **コンテナ化技術**: Docker

### テーブル設計・ER 図

![Atte Diagram](atte.drawio.png)

### 環境構築

- **PHP**: 8.3.6
- **MySQL**: 10.3.39
- **Composer**: 2.8.5
- **Docker**: 28.0.4
- **Laravel Framework**: 8.83.27

- ＊ご使用の PC に合わせて各種必要なファイル(.env や docker-compose.yml 等)は作成、編集してください。

####クローン作製手順

1. GitHub リポジトリのクローン

```bash
git clone https://github.com/ponponmama/20240625-erika_hara-atte.git
```

```bash
cd 20240625-erika_hara-atte
```

2. 必要なパッケージのインストール

```bash
sudo apt-get update
```

Docker コンテナのビルドと起動

```bash
docker-compose up -d --build
```

3. Composer を使用した依存関係のインストール

- github からクローンを作成するといくつかのフォルダが足りません。src に setup.sh を作成してあります。プロジェクトはフレームワーク内にインストールするので、先にフォルダ作成お願いします。

- 3-1. コンテナに入ります。

```bash
docker-compose exec php bash
```

- 3-2. スクリプトに実行権限を付与します。

```bash
chmod +x setup.sh
```

```bash
./setup.sh
```

- 以下のフォルダが作成されます

```
      bootstrap/cache \
      storage \
      storage/framework/cache \
      storage/framework/cache/data \
      storage/framework/sessions \
      storage/framework/testing \
      storage/framework/views \
      storage/logs \
      storage/logs/app \
      storage/logs/app/public \
```

<br>

#### "ディレクトリが正常に作成されました。" ← このメッセージが出ます。<br>

<br>

- 3-3 Docker 環境で PHP コンテナに入り、依存関係をインストールします。<br>

```bash
docker-compose exec php bash
```

```bash
composer install
```

<br>

4. 環境設定ファイルの設定

- .env.example ファイルを .env としてコピーし、必要に応じてデータベースなどの設定を行います。

```bash
cp .env.example .env
```

- 環境設定を更新した後、設定キャッシュをクリアするために以下のコマンドを実行します。これにより、新しい設定がアプリケーションに反映されます。

```bash
docker-compose exec php bash
```

```bash
php artisan config:clear
```

この手順は、特に環境変数が更新された後や、`.env` ファイルに重要な変更を加えた場合に重要です。設定キャッシュをクリアすることで、古い設定が引き続き使用されることを防ぎます。

4. アプリケーションキーの生成

```bash
php artisan key:generate
```

5. データベースのマイグレーション

```bash
php artisan migrate
```

6. データベースシーダーの実行

```bash
php artisan db:seed
```

＊マイグレーションとシーダーを同時に実行する場合

```bash
php artisan migrate --seed
```

### メール設定

プロジェクトでは開発環境でのメール送信のテストに MailHog を使用しています。

```
🎯 MailHogの役割
メール送信のテスト: アプリケーションから送信されたメールを実際には送信せずにキャッチ
Web UI: ブラウザでメール内容を確認できる（通常は http://localhost:8025）
開発環境専用: 本番環境では使用しない
```

※　このファイルは開発環境でメール機能をテストするために必要なツールです。本番環境にはデプロイしないでください！

- Laravel のメール設定で MailHog を SMTP サーバーとして設定
- 会員登録時の認証メールが MailHog でキャッチされる
- http://localhost:8025 でメール内容を確認できる
- テストでは Mail::fake()を使用してメール送信をモック

**1. docker-compose.yml の設定確認**

`docker-compose.yml`に既に MailHog の設定が含まれています：

```yml
mailhog:
  image: mailhog/mailhog:latest
  ports:
    - "1025:1025"
    - "8025:8025"
```

**2. .env ファイルへの設定追加**

下の設定を `.env` ファイルに追加してください。これにより、開発中のメール送信を安全にテストすることができます。

```env
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

**注意**: `MAIL_FROM_ADDRESS`の設定がないとメール送信が正常に動作しない場合があります。

UI は [http://localhost:8025](http://localhost:8025) で確認できます

### URL

- **開発環境:** [http://localhost/](http://localhost/)
- **phpMyAdmin:** [http://localhost:8080/](http://localhost:8080/)
- **MailHog UI:** [http://localhost:8025](http://localhost:8025)
