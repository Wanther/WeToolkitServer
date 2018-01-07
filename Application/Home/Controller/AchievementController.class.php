<?php
namespace Home\Controller;

use Think\Controller;

class AchievementController extends Controller {
    public function index($achievementId){

        $achievement = M('Achievement')->find($achievementId);

        $this->assign('achievement', $achievement);

        $this->display();
    }

    public function getQueryLink($name, $email) {
    	$AchievementItem = M('AchievementItem');
    	$achievement = $AchievementItem->where(array('name'=>$name, 'email'=>$email))->find();

    	if (!$achievement) {
    		$this->error('无法找到对应记录，请确认姓名和邮箱正确');
    	}

        $parent = M('Achievement')->find($achievement['pid']);

    	$detail = json_decode($achievement['detail'], true);
    	unset($achievement['detail']);

    	$this->assign('achievement', $achievement);
    	$this->assign('detail', $detail);
    	$message = $this->fetch('Achievement:achievement_email');

    	Vendor('PHPMailer.PHPMailerAutoload');
    	$mail = new \PHPMailer();
    	$mail->isSMTP();
    	$mail->CharSet = 'utf-8';
    	$mail->Host = 'smtp.163.com';
    	$mail->SMTPSecure = 'ssl';
    	$mail->Port = 465;
    	$mail->SMTPAuth = true;
    	$mail->Username = 'wanghepro@163.com';
    	$mail->Password = 'wanghepro0507';
    	$mail->addAddress($achievement['email'], $achievement['name']);
    	$mail->setFrom('wanghepro@163.com', '成绩查询');
    	$mail->Subject = $parent['name'];
    	$mail->isHTML(true);
    	$mail->Body = $message;

    	if (!$mail->send()) {
    		$this->error('邮件发送失败'.$mail->ErrorInfo);
    	} else {
    		$this->success('邮件发送成功');
    	}
    	
    }
}