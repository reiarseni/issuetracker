<?php

namespace NotifyBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Bundle pensado para tratar:
 *   Notificaciones especificas para un usuario, el usuario logeado, segun el rol o especifico por usuario
 *   Guardar y mostrar eventos especiales del sistema como los log de inicio de sesion de usuarios, para conocimiento de administradores
 *   Captura los log de inicio de sesion exito/fallos
 *   Captura con un eventlistener eventos lanzados desde la aplicacion como la creacion de un issue, cambio de estado, etc
 *
 * Class NotifyBundle
 * @package NotifyBundle
 */
class NotifyBundle extends Bundle
{
}
