import Vue from "vue";
import VueRouter from "vue-router";

import NotFound from "./components/NotFound";
import Dashboard from "./components/Dashboard";
import Login from "./components/auth/Login";

import Logout from "./components/auth/Logout";
import PasswordRequest from "./components/auth/PasswordRequest";
import PasswordReset from "./components/auth/PasswordReset";
import ContactSupport from "./components/ContactSupport";


import Profile from "./components/profile/Profile";
import Users from "./components/users/Users";
import Roles from "./components/roles/Roles";
import Menus from "./components/menus/Menus";
import System from "./components/system/System";
import TeacherExclusions from "./components/exclusions/TeacherExclusions";

import Permissions from "./components/permissions/Permissions";
import Surveys from "./components/surveys/Index";
import EvaluationsIndex from "./components/evaluations/Index";
import EvaluationsForm from "./components/evaluations/Form";
import EvaluationQualify from "./components/evaluations/Qualify";
import EvaluationTeachers from "./components/evaluations/Teachers";
import Scales from "./components/scales/Index";
import Levels from "./components/levels/Index";
import Reports from "./components/reports/Reports";
import Student from "./components/students/Student";

Vue.use(VueRouter);

var  pathCrud = ':id(\\d+|create)/:action(\\edit|delete)?';

const routes = [

    { path: "/dashboard", name: "dashboard", component: Dashboard, meta: { requiresAuth: true } },
    { path: "/scales", name: "scales", component: Scales, meta: { requiresAuth: true } },
    { path: "/levels", name: "levels", component: Levels, meta: { requiresAuth: true } },
    { path: "/surveys", name: "survey", component: Surveys, meta: { requiresAuth: true } },

    { path: "/evaluations", name: "evaluation.index", component: EvaluationsIndex },
    { path: "/evaluations/"+pathCrud, name: "evaluation.form", component: EvaluationsForm },
    { path: "/evaluations/:evaluation_id/teachers", name: "evaluation.teachers", component: EvaluationTeachers },
    { path: "/evaluations/:evaluation_id/teachers/:teacher_code/qualify", name: "evaluation.qualify", component: EvaluationQualify },
    { path: "/reports", name: "reports", component: Reports },

    { path: "/settings/exclusions/teachers", name: "teacherExclusions", component: TeacherExclusions },

    { path: "/settings/menus", name: "menus", component: Menus },
    { path: "/settings/users", name: "users", component: Users },
    { path: "/settings/roles", name: "roles", component: Roles },
    { path: "/settings/permissions", name: "permissions", component: Permissions },
    { path: "/settings/system", name: "system", component: System },

    { path: "/profile", name: "profile", component: Profile, meta: { requiresAuth: true } },

    { path: '/login', component: Login, name: 'login' },

    { path: '/logout', component: Logout, name: 'logout'},

    { path: '/password/reset', component: PasswordRequest, name: 'password.request' },

    { path: '/password/reset/:token', component: PasswordReset, name: 'password.reset' },

    { path: "/student/:code", name: "student", component: Student }, // public path

    { path: "/contact-support", name: "ContactSupport", component: ContactSupport },

    { path: '*', name: "NotFound", component: NotFound },
];

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})

export default router;