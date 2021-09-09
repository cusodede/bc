<?php
declare(strict_types = 1);

namespace app\modules\api\controllers;

use app\models\site\UpdatePasswordForm;
use app\models\sys\users\Users;
use app\modules\api\models\FrontRestorePasswordForm;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Some API logic for handling user's sensitive information.
 */
class UserController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors(): array
	{
		return [
			'contentNegotiator' => [
				'class' => ContentNegotiator::class,
				'formats' => [
					'application/json' => Response::FORMAT_JSON
				]
			],
			'verbFilter' => [
				'class' => VerbFilter::class,
				'actions' => $this->verbs()
			]
		];
	}

	/**
	 * Формирование ссылки для восстановления пароля на почту пользователя. TODO: delete return value after test
	 * @param string $email
	 * @return array
	 */
	public function actionGetRestorePasswordLink(string $email): array
	{
		$form = new FrontRestorePasswordForm(['email' => $email]);
		$form->sendCode();

		return ['code' => Users::findByEmail($email)->restore_code];
	}

	/**
	 * Сброс пароля.
	 * @return void
	 * @throws BadRequestHttpException
	 * @throws NotFoundHttpException
	 */
	public function actionRestorePassword(): void
	{
		$user = Users::findByRestoreCode(Yii::$app->request->post('code'));
		if (null === $user) {
			throw new NotFoundHttpException('Некорректный код восстановления.');
		}

		$form = new UpdatePasswordForm(compact('user'));
		$form->load(Yii::$app->request->post(), '');

		$form->newPasswordRepeat = $form->newPassword;
		if (!$form->doUpdate(false)) {
			throw new BadRequestHttpException(current($form->getFirstErrors()));
		}
	}

	/**
	 * Проверка действительности кода восстановления пароль. HTTP 200 в случае прохождения валидации, 500 - если код невалидный.
	 * @param string $code
	 * @return void
	 * @throws BadRequestHttpException
	 * @throws NotFoundHttpException
	 */
	public function actionCheckRestorePasswordCode(string $code): void
	{
		$user = Users::findByRestoreCode($code);
		if (null === $user) {
			throw new NotFoundHttpException('Некорректный код восстановления.');
		}

		if ($user->isExpiredRestoreCode()) {
			throw new BadRequestHttpException('Код восстановления просрочен.');
		}
	}

	/**
	 * {@inheritdoc}
	 */
	protected function verbs(): array
	{
		return ['get-restore-password-link' => ['GET'], 'restore-password' => ['POST'], 'check-restore-password-code' => ['GET']];
	}
}