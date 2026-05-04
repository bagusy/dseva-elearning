<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        Schema::create('phishing_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('logo');
            $table->timestamps();
        });
        DB::table('phishing_templates')->insert([
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Adobe',
                'logo'=>'/images/logo/adobe.png',
                'created_at'=>now()->addSeconds(1)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Apple',
                'logo'=>'/images/logo/apple.png',
                'created_at'=>now()->addSeconds(2)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Coinbase',
                'logo'=>'/images/logo/coinbase.png',
                'created_at'=>now()->addSeconds(3)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Gmail Sheet',
                'logo'=>'/images/logo/gmail-sheet.png',
                'created_at'=>now()->addSeconds(4)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Microsoft',
                'logo'=>'/images/logo/microsoft.png',
                'created_at'=>now()->addSeconds(5)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Skype',
                'logo'=>'/images/logo/skype.png',
                'created_at'=>now()->addSeconds(6)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Stripe',
                'logo'=>'/images/logo/stripe.png',
                'created_at'=>now()->addSeconds(7)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Gmail Docs',
                'logo'=>'/images/logo/gmail-docs.png',
                'created_at'=>now()->addSeconds(8)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Cloudflare',
                'logo'=>'/images/logo/cloudflare.png',
                'created_at'=>now()->addSeconds(9)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Netflix',
                'logo'=>'/images/logo/netflix.png',
                'created_at'=>now()->addSeconds(10)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Microsoft Teams',
                'logo'=>'/images/logo/microsoft-teams.png',
                'created_at'=>now()->addSeconds(11)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Trello',
                'logo'=>'/images/logo/trello.png',
                'created_at'=>now()->addSeconds(12)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Gmail Slides',
                'logo'=>'/images/logo/gmail-slide.png',
                'created_at'=>now()->addSeconds(13)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Chat GPT',
                'logo'=>'/images/logo/chat-gpt.png',
                'created_at'=>now()->addSeconds(14)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Chat GPT 2',
                'logo'=>'/images/logo/chat-gpt2.png',
                'created_at'=>now()->addSeconds(15)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Twitter',
                'logo'=>'/images/logo/twitter.png',
                'created_at'=>now()->addSeconds(16)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Paypal',
                'logo'=>'/images/logo/paypal.png',
                'created_at'=>now()->addSeconds(17)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Paypal Get $5 Free',
                'logo'=>'/images/logo/paypal-5-dollar.png',
                'created_at'=>now()->addSeconds(18)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Facebook',
                'logo'=>'/images/logo/facebook.png',
                'created_at'=>now()->addSeconds(19)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Office 365',
                'logo'=>'/images/logo/office.png',
                'created_at'=>now()->addSeconds(20)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Microsoft Sharepoint',
                'logo'=>'/images/logo/microsoft-sharepoint.png',
                'created_at'=>now()->addSeconds(21)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Amazon',
                'logo'=>'/images/logo/amazon.png',
                'created_at'=>now()->addSeconds(22)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Slack',
                'logo'=>'/images/logo/slack.png',
                'created_at'=>now()->addSeconds(23)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Instagram',
                'logo'=>'/images/logo/instagram.png',
                'created_at'=>now()->addSeconds(24)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Linkedin',
                'logo'=>'/images/logo/linkedin.png',
                'created_at'=>now()->addSeconds(25)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Dropbox',
                'logo'=>'/images/logo/dropbox.png',
                'created_at'=>now()->addSeconds(26)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Pinterest',
                'logo'=>'/images/logo/pinterest.png',
                'created_at'=>now()->addSeconds(27)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Uber',
                'logo'=>'/images/logo/uber.png',
                'created_at'=>now()->addSeconds(28)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Tokopedia',
                'logo'=>'/images/logo/tokopedia.png',
                'created_at'=>now()->addSeconds(29)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Shopee',
                'logo'=>'/images/logo/shopee.png',
                'created_at'=>now()->addSeconds(30)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Lazada',
                'logo'=>'/images/logo/lazada.png',
                'created_at'=>now()->addSeconds(31)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Gojek',
                'logo'=>'/images/logo/gojek.png',
                'created_at'=>now()->addSeconds(32)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Zoom',
                'logo'=>'/images/logo/zoom.png',
                'created_at'=>now()->addSeconds(33)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Binance',
                'logo'=>'/images/logo/binance.png',
                'created_at'=>now()->addSeconds(34)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Indodax',
                'logo'=>'/images/logo/indodax.png',
                'created_at'=>now()->addSeconds(35)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Stockbit',
                'logo'=>'/images/logo/stockbit.png',
                'created_at'=>now()->addSeconds(36)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Bibit',
                'logo'=>'/images/logo/bibit.png',
                'created_at'=>now()->addSeconds(37)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'JnT',
                'logo'=>'/images/logo/jnt.png',
                'created_at'=>now()->addSeconds(38)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'JNE',
                'logo'=>'/images/logo/jne.png',
                'created_at'=>now()->addSeconds(39)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Sicepat',
                'logo'=>'/images/logo/sicepat.png',
                'created_at'=>now()->addSeconds(40)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Grab',
                'logo'=>'/images/logo/grab.png',
                'created_at'=>now()->addSeconds(41)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Traveloka',
                'logo'=>'/images/logo/traveloka.png',
                'created_at'=>now()->addSeconds(42)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Bukalapak',
                'logo'=>'/images/logo/bukalapak.png',
                'created_at'=>now()->addSeconds(43)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Ajaib',
                'logo'=>'/images/logo/ajaib.png',
                'created_at'=>now()->addSeconds(44)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Tiket.com',
                'logo'=>'/images/logo/tiket.png',
                'created_at'=>now()->addSeconds(45)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Blibli',
                'logo'=>'/images/logo/blibli.png',
                'created_at'=>now()->addSeconds(46)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Ninja Express',
                'logo'=>'/images/logo/ninja.png',
                'created_at'=>now()->addSeconds(47)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Kredivo',
                'logo'=>'/images/logo/kredivo.png',
                'created_at'=>now()->addSeconds(48)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Akulaku',
                'logo'=>'/images/logo/akulaku.png',
                'created_at'=>now()->addSeconds(49)
            ],
            [
                'id'=> Str::orderedUuid()->toString(),
                'name'=>'Dana',
                'logo'=>'/images/logo/dana.png',
                'created_at'=>now()->addSeconds(50)
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('phishing_templates');
    }
};
