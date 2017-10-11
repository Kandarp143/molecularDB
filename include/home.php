<div class="post">
    <h1 class="title">Welcome to the database of the "Molecular Models of the Boltzmann-Zuse Society" </h1>
    <div class="entry">
		<!--
        <p>Database "Molecular Models of Boltzmann-Zuse Society" developed by:</p>
        <div>
            <table>
                <tbody>
                <tr>
                    <td width="20%">
                        <a href="http://thermo.mv.uni-kl.de" target="_blank">
                            <img src="img/logo_tukl.png" alt="TU Kaiserslautern" width="180px"/>
                        </a>
                    </td>
                    <td></td>
                    <td></td>
                    <td>
                        <strong>Laboratory of Engineering Thermodynamics (LTD)</strong><br/>
                        University of Kaiserslautern,<br/>
                        Germany
                    </td>
                </tr>
                <tr>
                    <td width="20%">
                        <a href="http://thet.uni-paderborn.de" target="_blank">
                            <img src="img/logo_p.png" alt="University of Paderborn" width="180px"/>
                        </a>
                    </td>
                    <td></td>
                    <td></td>
                    <td>
                        <strong>Laboratory of Thermodynamics and Energy Technology (ThEt)</strong><br/>
                        University of Paderborn,<br/>
                        Germany
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <p>
		-->
		<p>This database is developed by members of the Boltzmann-Zuse Society.</p>
		
            The molecular models which are documented here are rigid classical mechanical
            force fields for low-molecular fluids, consisting of 12-6 Lennard-Jones
            interaction sites, point charges, dipoles and quadrupoles.
            They can be used with molecular simultion tools like <a href="http://www.ms-2.de"
                                                                    target="_blank"><b><i>ms2</i></b></a>
            and <a
                    href="http://www.ls1-mardyn.de/home.html" target="_blank"><b><i>ls1 mardyn</i></b></a>.
        </p>
        <?php if (isset($_SESSION["act"])) { ?>
            <p>
                The entire database can be downloaded in multiple formats <a href="downAll.php"><b>HERE</b></a>. A
                detailed search functionality in the <a href="mollist.php"><b>list of modeled fluids</b></a> is
                implemented.
            </p>
        <?php } ?>
    </div>
</div>