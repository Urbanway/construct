<?php
namespace Construct\Internal\Administrators;


class Model
{

    public static function get($id)
    {
        return constructQuery()->selectRow('administrator', '*', array('id' => $id));
    }

    public static function getAll()
    {
        return constructQuery()->selectAll('administrator', '*', [], 'ORDER BY `id` ASC');
    }

    public static function delete($id)
    {
        constructQuery()->delete('administrator', array('id' => $id));
    }

    public static function getByUsername($username)
    {
        return constructQuery()->selectRow('administrator', '*', array('username' => $username));
    }

    public static function getById($id)
    {
        return constructQuery()->selectRow('administrator', '*', array('id' => $id));
    }

    public static function getByEmail($email)
    {
        return constructQuery()->selectRow('administrator', '*', array('email' => $email));
    }

    public static function addAdministrator($username, $email, $password)
    {
        $data = array(
            'username' => $username,
            'email' => $email,
            'hash' => self::passwordHash($password)
        );
        return constructQuery()->insert('administrator', $data);
    }

    public static function sendResetPasswordLink($userId)
    {
        $user = self::get($userId);
        if (!$user) {
            throw new \Construct\Exception("User doesn't exist");
        }

        $urlData = array(
            'sa' => 'Admin.passwordReset',
            'id' => $userId,
            'secret' => self::generatePasswordResetSecret($userId)
        );

        $contentData = array(
            'link' => ipActionUrl($urlData)
        );
        $content = ipView('view/passwordResetContent.php', $contentData)->render();

        $emailData = array(
            'content' => $content
        );

        $email = ipEmailTemplate($emailData);

        $from = ipGetOptionLang('Config.websiteEmail');
        $fromName = ipGetOptionLang('Config.websiteTitle');
        $subject = __('Password reset instructions', 'Construct-admin', false);
        $to = $user['email'];
        $toName = $user['username'];
        ipSendEmail($from, $fromName, $to, $toName, $subject, $email);

    }


    public static function setUserPassword($userId, $password)
    {
        constructQuery()->update('administrator', array('hash' => self::passwordHash($password)), array('id' => $userId));
    }

    public static function update($userId, $username, $email, $password)
    {
        $data = array(
            'email' => $email,
            'username' => $username
        );

        if ($password) {
            $data['hash'] = self::passwordHash($password);
        }

        constructQuery()->update('administrator', $data, array('id' => $userId));
    }


    public static function resetPassword($userId, $secret, $password)
    {
        $user = self::get($userId);
        if (!$user) {
            throw new \Construct\Exception("User doesn't exist");
        }

        if (empty($user['resetSecret']) || $user['resetTime'] < time() - ipGetOption(
                'Config.passwordResetLinkExpire',
                60 * 60 * 24
            )
        ) {
            throw new \Construct\Exception('Invalid password reset link');
        }

        if ($user['resetSecret'] != $secret) {
            throw new \Construct\Exception('Password reset link has expired');
        }

        constructQuery()->update('administrator', array('hash' => self::passwordHash($password)), array('id' => $userId));
    }

    private static function generatePasswordResetSecret($userId)
    {
        $secret = md5(ipConfig()->get('sessionName') . uniqid());
        $data = array(
            'resetSecret' => $secret,
            'resetTime' => time()
        );
        constructQuery()->update('administrator', $data, array('id' => $userId));
        return $secret;
    }

    public static function checkPassword($userId, $password)
    {
        $user = self::get($userId);
        return self::checkHash($password, $user['hash']);
    }

    private static function passwordHash($password)
    {
        $stretching = ipGetOption('Admin.passwordStretchingIterations', 8);
        $hasher = new \Construct\Lib\PasswordHash($stretching, ipGetOption('Construct.portableAdminHashes', true));
        return $hasher->HashPassword($password);
    }

    private static function checkHash($password, $storedHash)
    {
        $hasher = new \Construct\Lib\PasswordHash(8, ipGetOption('Construct.portableAdminHashes', true));
        $hasher->CheckPassword($password, $storedHash);
        return $hasher->CheckPassword($password, $storedHash);
    }

}
