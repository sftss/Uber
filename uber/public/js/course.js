$("#editCourseModal").on("show.bs.modal", function (event) {
  var button = $(event.relatedTarget);
  var courseId = button.data("id");
  var chauffeur = button.data("chauffeur");
  var depart = button.data("depart");
  var arrivee = button.data("arrivee");
  var prix = button.data("prix");
  var date = button.data("date");
  var duree = button.data("duree");
  var temps = button.data("temps");

  // Remplir les champs du formulaire avec les valeurs existantes
  $("#course_id").val(courseId);
  $("#chauffeur").val(chauffeur);
  $("#depart").val(depart);
  $("#arrivee").val(arrivee);
  $("#prix").val(prix);
  $("#date").val(date);
  $("#duree").val(duree);
  $("#temps").val(temps);
});

$("#saveChangesBtn").click(function () {
  var courseId = $("#course_id").val();
  var formData = $("#editCourseForm").serialize();

  $.ajax({
    url: "/courses/" + courseId,
    type: "PUT",
    data: formData,
    success: function (response) {
      $("#editCourseModal").modal("hide");
      location.reload(); // Recharger la page après la mise à jour
    },
    error: function (error) {
      alert("Erreur lors de la modification");
    },
  });
});
