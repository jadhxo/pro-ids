import { Routes, Route, Navigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

// AUTH
import LoginPage from "../pages/LoginPage";
import RegisterPage from "../pages/RegisterPage";

// STUDENT
import MyLearningPage from "../pages/MylearningPage";
import CourseCatalogPage from "../pages/CourseCatalogPage";
import MyProgressPage from "../pages/MyProgressPage";
import CertificatesPage from "../pages/CertificatesPage";
import CourseLessonPage from "../pages/CourseLessonPage";
import CertificateViewPage from "../pages/CertificateViewPage";

// INSTRUCTOR
import InstructorLayout from "../layout/InstructorLayout";
import InstructorOverviewPage from "../pages/instructor/InstructorOverviewPage";
import InstructorCoursesPage from "../pages/instructor/InstructorCoursesPage";
import InstructorAnalyticsPage from "../pages/instructor/InstructorAnalyticsPage";
import InstructorSettingsPage from "../pages/instructor/InstructorSettingsPage";
import InstructorCreateEditCoursePage from "../pages/instructor/InstructorCreateEditCoursePage";

export default function AppRoutes() {
  const { isAuthenticated, role } = useAuth();

  // 🔒 Not logged in
  if (!isAuthenticated) {
    return (
      <Routes>
        <Route path="/login" element={<LoginPage />} />
        <Route path="/register" element={<RegisterPage />} />
        <Route path="*" element={<Navigate to="/login" replace />} />
      </Routes>
    );
  }

  // 🎓 STUDENT MODE (exclusive)
  if (role === "student") {
    return (
      <Routes>
        <Route path="/" element={<MyLearningPage />} />
        <Route path="/catalog" element={<CourseCatalogPage />} />
        <Route path="/progress" element={<MyProgressPage />} />
        <Route path="/certificates" element={<CertificatesPage />} />
        <Route
          path="/course/:id/lesson/:lessonId"
          element={<CourseLessonPage />}
        />
        <Route
          path="/student/certificates/:id"
          element={<CertificateViewPage />}
        />
        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    );
  }

  // 🧑‍🏫 INSTRUCTOR MODE (exclusive + layouted)
if (role === "instructor") {
  return (
    <Routes>
      <Route element={<InstructorLayout />}>
        <Route
          path="/instructor/overview"
          element={<InstructorOverviewPage />}
        />
        <Route
          path="/instructor/courses"
          element={<InstructorCoursesPage />}
        />
        <Route
          path="/instructor/analytics"
          element={<InstructorAnalyticsPage />}
        />
        <Route
          path="/instructor/settings"
          element={<InstructorSettingsPage />}
        />
        <Route
          path="/instructor/courses/new"
          element={<InstructorCreateEditCoursePage />}
        />
        <Route
          path="/instructor/courses/:id/edit"
          element={<InstructorCreateEditCoursePage />}
        />
        <Route
          path="*"
          element={<Navigate to="/instructor/overview" replace />}
        />
      </Route>
    </Routes>
  );
}


  return null;
}
