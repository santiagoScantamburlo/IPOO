<?php

namespace Ipoo\Tps\Tp1\Ej10;

use Ipoo\BaseClass;

/**
 * @property string $username
 * @property string $password
 * @property string[] $usedPasswords
 * @property int $passwordsPointer
 */
class Login extends BaseClass
{
    protected array $attributes = ['username', 'password', 'usedPasswords', 'passwordsPointer'];

    protected array $hidden = ['usedPasswords', 'passwordsPointer'];

    public function __construct(array $_data)
    {
        $_data = array_merge($_data, [
            'usedPasswords' => [$_data['password']],
            'passwordsPointer' => 1,
        ]);
        parent::__construct($_data);
    }

    public function changePassword(string $newPassword): bool
    {
        if (in_array($newPassword, $this->usedPasswords)) {
            return false;
        }

        $pointer = $this->passwordsPointer >= 4 ? $this->passwordsPointer % 4 : $this->passwordsPointer;

        $usedPasswords = $this->usedPasswords;
        $usedPasswords[$pointer] = $newPassword;

        $this->fill([
            'password' => $newPassword,
            'usedPasswords' => $usedPasswords,
            'passwordsPointer' => $pointer + 1,
        ]);

        return true;
    }
}
