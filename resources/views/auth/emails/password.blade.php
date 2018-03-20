<style media="screen" type="text/css">
.emailParagraph{
    font-family:Avenir,Helvetica,sans-serif;
    box-sizing:border-box;
    color:#3a3939;
    font-size:1.1em;
    line-height:1.5em;
    margin-top:0;
    text-align:left
}
.resetButton{
    font-family:Avenir,Helvetica,sans-serif;
    box-sizing:border-box;
    border-radius:3px;
    color:#fff;
    display:inline-block;
    text-decoration:none;
    background-color:#43425D;
    border-top:10px solid #43425D;
    border-right:18px solid #43425D;
    border-bottom:10px solid #43425D;
    border-left:18px solid #43425D;
    margin: 0 auto;
}
#aggregatorTitle{
    color: #43425D;
    font-family:Helvetica,sans-serif;
    text-align: center;
    padding: 10px;
    background-color: #F0F0F7;
}
.kdgBody{
    margin: 0 auto;
    width: 50%;
    padding: 10px; 
}
small{
    text-align: center;
    display: block;
    color: #9A99AA;
    padding: 10px;
    background-color: #F0F0F7;
}
.wrapper {
  display: flex;
  justify-content: center;
}
</style>
<h1 id="aggregatorTitle" style="color: #43425D;
    font-family:Helvetica,sans-serif;
    text-align: center;
    padding: 10px;
    background-color: #F0F0F7;">KDG Aggregator</h1>

<div class="kdgBody" style="margin: 0 auto;
    width: 50%;
    padding: 10px; ">
<p style="font-family:Avenir,Helvetica,sans-serif;
    box-sizing:border-box;
    color:#3a3939;
    font-size:1.1em;
    line-height:1.5em;
    margin-top:0;
    text-align:left">Hello there,</p>

<p style="font-family:Avenir,Helvetica,sans-serif;
    box-sizing:border-box;
    color:#3a3939;
    font-size:1.1em;
    line-height:1.5em;
    margin-top:0;
    text-align:left">You are receiving this email because we received a password reset request for your account.</p>

<p style="font-family:Avenir,Helvetica,sans-serif;
    box-sizing:border-box;
    color:#3a3939;
    font-size:1.1em;
    line-height:1.5em;
    margin-top:0;
    text-align:left;
    display: flex;
    justify-content: center;"><a style="font-family:Avenir,Helvetica,sans-serif;
    box-sizing:border-box;
    border-radius:3px;
    color:#fff;
    display:inline-block;
    text-decoration:none;
    background-color:#43425D;
    border-top:10px solid #43425D;
    border-right:18px solid #43425D;
    border-bottom:10px solid #43425D;
    border-left:18px solid #43425D;
    margin: 0 auto;" href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}" class="resetButton" target="_blank">
Reset Password
</a></p>

<p style="font-family:Avenir,Helvetica,sans-serif;
    box-sizing:border-box;
    color:#3a3939;
    font-size:1.1em;
    line-height:1.5em;
    margin-top:0;
    text-align:left">If you did not request a password reset, no further action is required.</p>

<p style="font-family:Avenir,Helvetica,sans-serif;
    box-sizing:border-box;
    color:#3a3939;
    font-size:1.1em;
    line-height:1.5em;
    margin-top:0;
    text-align:left">Regards,</p>

<p style="font-family:Avenir,Helvetica,sans-serif;
    box-sizing:border-box;
    color:#3a3939;
    font-size:1.1em;
    line-height:1.5em;
    margin-top:0;
    text-align:left">{{ env('APP_Name') }}</p>



<div style="text-align: center;
    display: block;
    color: #9A99AA;
    padding: 10px;
    background-color: #F0F0F7;">
<p>
<small style="color: #3a3939">
    If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
</small>
</p>
<p>
<small>
<a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
</small>
</p>
</div>
</div>
