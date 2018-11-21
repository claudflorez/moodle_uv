<?php
	require('../config.php');
	$PAGE->set_title('Instrucciones Inscripción Cursos');
	$PAGE->set_heading(" ");
	$PAGE->navbar->add("Instrucciones Inscripción Cursos en el Campus Virtual");
	echo $OUTPUT->header();
?>
</br></br>
<!-- Título -->
<div style="text-align:center;">
	<span style="font-size:large;">
		<strong>
			<span style="font-size:larger;">Instrucciones para la inscripción de los cursos en el</span><br>
			<span style="font-size:larger;"> Campus Virtual de la Universidad del Valle</span><br>
		</strong>
	</span>
</div>

<!-- Contenendor principal -->
<div class="container">
	<div class="content">
		<p class="title-2">Para que usted pueda gestionar sus&nbsp; cursos en la plataforma Campus Virtual debe realizar el proceso de inscripción. Éste varía dependiendo de si es un curso regular&nbsp; o de extensión. </p>
		<div>
			<ul>
				<li style="text-align:justify;">
					<strong>Cursos que están registrados en el Sistema de Registro Académico</strong><br>
					(recomendamos leer completamente las instrucciones antes de hacer clic 	sobre cualquier enlace)
				</li>
			</ul>
		</div>
		<div style="text-align:justify;">
			<ol>
				<li style="text-align:justify;">Hacer clic en el enlace 
					<a title="Inscripción de cursos en el Campus Virtual" href="https://campusvirtual.univalle.edu.co/InterfazRegistroACampus/" target="_blank">Interfaz Registro Académico a Campus Virtual Univalle.</a>
				</li>
				<li style="text-align:justify;">Ingrese con su nombre de usuario (número de cédula) y contraseña (si está registrado como usuario en el Campus Virtual ingrese su contraseña actual, de lo contrario use la asignada por Registro Académico para registrar las notas del semestre anterior) y siga las instrucciones consignadas en el sistema para la inscripción de cursos.
				</li>
				<li style="text-align:justify;">Al inscribir un curso a través de esta interfaz, los estudiantes matriculados quedarán registrados automáticamente en el Campus Virtual. Después de confirmar la inscripción, el curso estará disponible en el Campus Virtual y podrá acceder a él a través de la página <a title="Campus Virtual Universidad del Valle" href="http://campusvirtual.univalle.edu.co" target="_blank">http://campusvirtual.univalle.edu.co</a>
				</li>

				<li style="text-align:justify;">Para adicionar o eliminar estudiantes envíe un correo electrónico a la Administración del Campus Virtual
				<a title="Correo electrónico Campus Virtual" href="mailto:campusvirtual@correounivalle.edu.co">campusvirtual@correounivalle.edu.co</a> 
				anexando la información del curso, docente(s) y estudiantes en el formato de inscripción de cursos disponible en el siguiente enlace.
				<a title="Formato de inscripción de cursos" href="Formatos/Formato_creacion_cursos_CVUV.xls">Formato de inscripción de cursos</a>.
				</li>

			</ol>
			<strong>NOTA</strong>:
				<div style="text-align:justify;">Si olvidó su contraseña, envíe un correo electrónico a la Administración del Campus Virtual&nbsp; con su número de cédula y nombres completos.<br><br>
			</div>
		</div>
	</div><!-- GEstion de cursos en el sistema. -->

	<div class="content">
		<div style="text-align:justify;">
			<p class="title-2">
			</p> 
			<ul>
				<li><span>
						<strong>Cursos de extensión o especiales como Diplomados, Seminarios, etc.</strong>
					</span>
				</li>
			</ul>
			<ol>
				<li>Envíe un correo electrónico a la Administración del Campus Virtual&nbsp;
					<a title="Correo electrónico Campus Virtual" href="mailto:campusvirtual@correounivalle.edu.co"> campusvirtual@correounivalle.edu.co,
					</a> solicitando la inscripción de uno o varios cursos, anexando la información de(los) y estudiantes en el <a href="Formatos/Formato_creacion_cursos_CVUV.xls">Formato de inscripción de cursos</a>.
				</li>
				<li>La Administración del Campus Virtual le enviará un correo electrónico informando sobre la inscripción del curso, con los nombres de usuarios y contraseñas para ingresar al Campus, tanto de los docentes como de los estudiantes.</li>
				<li>Los cursos estarán disponibles en el Campus Virtual y podrá acceder a ellos a través de <a title="Campus Virtual Universidad del Valle" href="http://campusvirtual.univalle.edu.co" target="_blank">http://campusvirtual.univalle.edu.co</a>.
				</li>
			</ol>
		</div>
	</div>
	<p class="credits">
		<span class="edited">(Editado por <a href="https://campusvirtual.univalle.edu.co/moodle/user/view.php?id=128&amp;course=1">Desarrollo Administrador</a> - envío original Thursday, 10 de July de 2014, 11:53)
		</span>
	</p>
</div>
<?php
	echo $OUTPUT->footer();
?>