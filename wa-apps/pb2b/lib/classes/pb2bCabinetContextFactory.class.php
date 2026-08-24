<?php
class pb2bCabinetContextFactory
{
    private static function resolveRole(): ?pb2bCompanyRole
    {
        try {
            return pb2bCompanyRole::from(waRequest::param('role', '', waRequest::TYPE_STRING));
        } catch (\ValueError $e) {
            return null;
        }
    }

    public static function build(): pb2bCabinetContext
    {
        $user = wa()->getUser();
        $company = (new pb2bCompanyModel())->getByContact($user->getId());
        $role = self::resolveRole();

        return new pb2bCabinetContext($user, $company, $role);
    }
}