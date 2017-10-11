<!-- Paragraph 1 -->
<p>
    The geometry of the molecular models is displayed in the <a
            href="https://en.wikipedia.org/wiki/Z-matrix_(chemistry)" target="_blank">Z-matrix format</a>. The Z-matrix
    deﬁnes the structure of a molecular model not by Cartesian $x$, $y$, $z$-coordinates, but by internal
    coordinates.Due to the removal of translational and rotational degrees of freedom, the necessary number of
    parameters for the deﬁnition of a molecular geometry is $3N−6$ ($3N−5$ for linear molecules).
    <br/>
    Internal coordinates describe the location of the atoms with respect to each other. The position of an atom in space
    is uniquely described by three internal coordinates.
    <br/>
    The Z-matrix is a list of internal coordinates that uniquely describes the structure of a molecule. The atoms – or
    interaction sites – of the molecule are successively positioned in relation to sites that were deﬁned before. The
    position of each interaction site is deﬁned by one distance (mostly the bond length), one angle and one dihedral
    angle and point from one site to the next.
    <br/>
</p>

<!-- Figures-->
<div class="figure">
    <img src="img/nomen/Angle_Definition.PNG" width="220"/>
    <p></p>
    <img src=" img/nomen/Dihedral_Definition.PNG" width="350"/>
</div>
<p class="figure_label">Figure 1: Angle and dihedral deﬁnition as used in Z-Matrix. $\alpha$ defines the angle of site
    #3 to site #1 and #2. $\beta$ defines the dihedral of site #4 as 'out of the plane angle' to the sites #1, #2 and
    #3. </p>

<!-- Table : General Notation -->
<p>The general notation of the Z-Matrix is:<br/></p>
<table width="60%" style="margin-left: 10%">
    <tr>
        <td rowspan="6"
            style=" border-left: solid 1px black;border-top: solid 1px black;border-bottom: solid 1px black"></td>
        <td>1</td>
        <td>name<sub>1</sub></td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td style="width: 1px">-</td>
        <td rowspan="6"
            style=" border-right: solid 1px black;border-top: solid 1px black;border-bottom: solid 1px black"></td>

    </tr>
    <tr>
        <td>2</td>
        <td>name<sub>2</sub></td>
        <td>1</td>
        <td>distance<sub>2</sub></td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
    </tr>
    <tr>
        <td>3</td>
        <td>name<sub>3</sub></td>
        <td>2</td>
        <td>distance<sub>3</sub></td>
        <td>1</td>
        <td>angle<sub>3</sub></td>
        <td>-</td>
        <td>-</td>
    </tr>
    <tr>
        <td>4</td>
        <td>name<sub>4</sub></td>
        <td>3</td>
        <td>distance<sub>4</sub></td>
        <td>2</td>
        <td>angle<sub>4</sub></td>
        <td>1</td>
        <td>dihedral<sub>4</sub></td>
    </tr>
    <tr>
        <td>. <br/> .</td>
        <td>. <br/> .</td>
        <td>. <br/> .</td>
        <td>. <br/> .</td>
        <td>. <br/> .</td>
        <td>. <br/> .</td>
        <td>. <br/> .</td>
        <td>. <br/> .</td>
    </tr>
    <tr>
        <td><i>n</i></td>
        <td>name<sub><i>n </i></sub></td>
        <td><i>n</i>-1</td>
        <td>distance<sub><i>n</i></sub></td>
        <td><i>n</i>-2</td>
        <td>angle<sub><i>n</sub></i></td>
        <td><i>n</i>-3</td>
        <td>dihedral<sub><i>n</sub></i></td>
    </tr>
</table>

<!-- Paragraph 2 -->
<p>
    Each line deﬁnes the position of one site with respect to the positions deﬁned before. The site-ID in the ﬁrst
    column is used to uniquely identify each site. The site-name in the second column describes, what atoms or quality
    (charge distribution) of the molecule the site models. Each line consists of three geometrical speciﬁcations (one
    distance, one angle, and one dihedral angle) and for each of these one corresponding reference ID. That reference ID
    indicates to which of the before deﬁned sites the the distance, angle and/ or dihedral angle stands in relation.
    Table 3 shows the structure of Cyclobutane in the Z-matrix format as an example. The forth line indicates, that the
    site #4 has a distance of 1.881 <span>&#8491;</span> to site #3, an angle of 87.9092<sup>◦</sup> between site {#2 –
    #3 – #4} and a dihedral angle of -21.6121˚ between the sites {#1 – #2 – #3 – #4}.
    <br/>
</p>

<!-- Table : Z-Matrix Example -->
<table width="60%" style="margin-left: 18%">
    <tr style="border-bottom: solid 1px grey;">
        <td><b>Site-ID</b></td>
        <td><b>Site-name</b></td>
        <td><b>Ref.</b></td>
        <td><b>Distance / <span>&#8491;</span></b></td>
        <td><b>Ref.</b></td>
        <td><b>Angle / <span>&#xb0;</span></b></td>
        <td><b>Ref.</b></td>
        <td><b>Dihedral / <span>&#xb0;</span></b></td>
    </tr>
    <?php
    foreach ($zmatrix as $z):?>
        <?php if (array_key_exists($z[1], $maker)) { ?>
            <tr>
                <td colspan="8"></td>
            </tr>
        <?php } ?>

        <tr>
            <td><?php echo $z[9] ?></td>
            <td><?php echo toSubstanceTitle($z[2]) ?></td>
            <td><?php echo $z[3] ?></td>
            <td><?php echo $z[4] ?></td>
            <td><?php echo $z[5] ?></td>
            <td><?php echo $z[6] ?></td>
            <td><?php echo $z[7] ?></td>
            <td><?php echo $z[8] ?></td>
        </tr>
        <?php
    endforeach; ?>
</table>

<!-- List detail -->
<p><br/>The following points hold for the nomenclature of the Z-matrix geometries:</p>
<ol>
    <li> The ﬁrst site of each molecular model does not need any coordinates (one can say that it sets the origin). The
        second site only needs a distance to the ﬁrst atom, since the orientation in space is arbitrary. The third site
        needs one distance and one angle to be uniquely deﬁned. From the forth site on, besides the distance and the
        angle, the dihedral states, how the new site lies in space.
    </li>
    <li> Overlapping sites: if a site lies at the same spot as an other, it is enough to reference the already deﬁned
        site. No further information is needed, e.g. the ’<?php echo toSubstanceTitle('C7H8_I') ?>’-model for Toluene.
        All the point quadrupoles lie exactly on the above deﬁnes Lennard-Jones sites.
    </li>
    <li>
        Orientation of point-dipole and point-quadrupole: The orientation of those sites is denoted by a unity vector.
        It’s basis is located on the site of the point-dipole or point-quadrupole respectively. This orientation-vector
        is given by an extra line in the Z-matrix named dir., which follows the line where the position of the dipole or
        quadrupole is deﬁned. See the ’<?php echo toSubstanceTitle('C3H6O') ?>’-model for Acetone as an example.
    </li>
    <li>
        In cases where the model reduces the molecular structure so much, that a assignment of atoms to the sites is not
        unique anymore, the sites are labled <span style="font-family: myFirstFont">V, W, X, Y ,Z</span> etc. (for
        example the ’<?php echo toSubstanceTitle('CCl4_I') ?>’ model for Carbon tetrachloride with two Lennard-Jones
        sites and one quadrupole).

    </li>
</ol>