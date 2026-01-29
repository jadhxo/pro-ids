import { useEffect, useState } from "react";
import { apiRequest } from "../api/http";
import { Link } from "react-router-dom";

type CourseProgress = {
  id: number;
  title: string;
  progress: number;
};

export default function MyLearningPage() {
  const [courses, setCourses] = useState<CourseProgress[]>([]);

  useEffect(() => {
    apiRequest("/my-progress").then(setCourses);
  }, []);

  return (
    <div style={{ padding: 32 }}>
      <h1 style={{ fontSize: 28, fontWeight: 800 }}>
        My Learning
      </h1>

      {courses.map((course) => (
        <div
          key={course.id}
          style={{
            marginTop: 24,
            padding: 20,
            borderRadius: 16,
            background: "#ffffff",
          }}
        >
          <h3>{course.title}</h3>

          <div
            style={{
              height: 10,
              background: "#e5e7eb",
              borderRadius: 8,
              marginTop: 12,
            }}
          >
            <div
              style={{
                width: `${course.progress}%`,
                height: "100%",
                background: "#2f66e6",
                borderRadius: 8,
              }}
            />
          </div>

          <p style={{ marginTop: 8 }}>
            {course.progress}% completed
          </p>

          <Link to={`/course/${course.id}`}>
            Continue →
          </Link>
        </div>
      ))}
    </div>
  );
}
