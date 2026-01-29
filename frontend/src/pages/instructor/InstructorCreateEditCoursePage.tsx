import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { apiRequest } from "../../api/http";

export default function InstructorCreateEditCoursePage() {
  const { id } = useParams();
  const navigate = useNavigate();

  const [title, setTitle] = useState("");
  const [description, setDescription] = useState("");
  const [loading, setLoading] = useState(false);

  // Load existing course if editing
  useEffect(() => {
    if (!id) return;

    apiRequest("/instructor/courses").then(
      (courses) => {
        const course = courses.find(
          (c: any) => c.id === Number(id)
        );
        if (course) {
          setTitle(course.title);
          setDescription(course.description || "");
        }
      }
    );
  }, [id]);

  async function handleSubmit() {
    if (!title.trim()) return;

    setLoading(true);

    if (id) {
      // EDIT
      await apiRequest(
        `/instructor/courses/${id}`,
        {
          method: "PUT",
          body: JSON.stringify({
            title,
            description,
          }),
        }
      );
    } else {
      // CREATE
      await apiRequest("/instructor/courses", {
        method: "POST",
        body: JSON.stringify({
          title,
          description,
        }),
      });
    }

    navigate("/instructor/courses");
  }

  return (
    <div style={{ maxWidth: 600 }}>
      <h1>
        {id ? "Edit Course" : "Create Course"}
      </h1>

      <div style={{ marginBottom: 16 }}>
        <label>Course Title</label>
        <input
          value={title}
          onChange={(e) =>
            setTitle(e.target.value)
          }
          style={{
            width: "100%",
            padding: 12,
            marginTop: 4,
          }}
        />
      </div>

      <div style={{ marginBottom: 16 }}>
        <label>Description</label>
        <textarea
          value={description}
          onChange={(e) =>
            setDescription(e.target.value)
          }
          style={{
            width: "100%",
            padding: 12,
            marginTop: 4,
            minHeight: 120,
          }}
        />
      </div>

      <button
        onClick={handleSubmit}
        disabled={loading}
      >
        {loading
          ? "Saving…"
          : id
          ? "Save Changes"
          : "Create Course"}
      </button>
    </div>
  );
}
