<!-- Paragraph 1 -->
<p>
    All models in the database ”Molecular Models of the Boltzmann-Zuse Society” consist of Lennard-Jones 12-6
    interaction sites and a variation of point charges, point-dipoles and point quadrupoles. The last two are
    computationally much cheaper compared to the corresponding conﬁguration of point charges.
    <!-- Since point multipoles are not supported by some molecular dynamics simulation codes, we use the approach
        [put link to section underneath] of [but reference of Engin here] to transform point-dipoles and point quadrupoles straightforwardly to two or three point charges respectively. -->
</p>

<!-- Defination1 : LJ126-->
<span class="def">Lennard-Jones 12-6: </span>
<div class="def_content">
    <p>
        Repulsion and dispersion interaction between two particles $i,j$ of the same kind at a distance $r$ is modelled
        throughout the database by the standard Lennard-Jones 12-6 potential:
    </p>
    <div class="normen_equa">
        \begin{equation}
        u_{ij}^\mathrm{LJ}(r)=4\varepsilon\left[
        \left(\frac{\sigma}{r}\right)^{12}-\left(\frac{\sigma}{r}\right)^6\right].
        \end{equation}
    </div>
    <p>
        The potential model itself consists of two parts – the ﬁrst part with the positive sing represents the repulsion
        and the negative the attraction. The potential has two parameters: The size parameter $\sigma$ with a dimension
        of length deﬁnes the distance where the potential energy is zero and the energy-parameter $\varepsilon$, which
        deﬁnes the depth of the potential and thereby sets the dispersion energy.
        <br/>
    </p>
    <div class="figure"><img src="img/nomen/LJ.PNG"/></div>
    <p class="figure_label">Figure 2: Lennard-Jones potential between two particles.</p>
    <p>
        For <b>unlike interactions </b> – the interaction of two sites with different $\varepsilon$ and/ or $\sigma$ –
        mixing rules can be applied. The parameters $\eta_{kl}$ and $\xi_{kl}$ in eq. (2) and (3) are used to correct
        the binary interaction parameters of the components $k$ and $l$ (if necessary). $\eta_{kl}$ and $\xi_{kl}$ are
        mostly a constant for a certain mixture. The extensive study of the inﬂuence of diﬀerent mixing rules by
        Schnabel et al. <a href="reflist.php" target="_blank"> [Schnabel, 2007_C] </a> showed, that mixture bubble
        densities are accurately obtained using the arithmetic mean of the two size parameters $\sigma_k,~\sigma_l$ as
        proposed by the Lorentz combining rule (3). That results in $\eta_{kl}=1$ being a very accurate description of
        the unlike size parameter. The vapor pressure turns out to be dependent on both unlike Lennard-Jones parameters.
        It was therefore recommended by Schnabel et al. to adjust the unlike Lennard-Jones energy parameter to the
        vapour pressure.
    </p>
    <div class="normen_equa">
        \begin{equation}
        \sigma_{kl}=\eta_{kl}\frac{\sigma_k+\sigma_l}{2}\label{eq:sigma_combination}\\
        \end{equation}
    </div>
    <div class="normen_equa">
        \begin{equation}
        \varepsilon_{kl}=\xi_{kl}\sqrt{\varepsilon_k\varepsilon_l}
        \end{equation}


    </div>
</div>

<!-- Defination1 : Charge-->
<span class="def">Point Charge: </span>
<div class="def_content">
    <p>
        Point charges are ﬁrst order electrostatic interaction sites. These sites are indicated in the database with an
        ’e’.
        The electrostatic interaction between two point charges $q_i$ and $q_j$ is given by Coulomb’s law:
    </p>
    <div class="normen_equa">
        \begin{equation}
        u^\mathrm{ee}_{ij}(r_{ij})=\frac{1}{4\epsilon_0\pi}\frac{q_iq_j}{r_{ij}}
        \end{equation}
    </div>
    <p>with $q$ the magnitude of the charge and $r_{ij}$ the distance between two charges.</p>
</div>

<div class="figure"><img src="img/nomen/coulomb.PNG"/></div>
<p class="figure_label">Figure 3: Coulomb potential between two point charges.</p>

<!-- Defination1 : Dipole-->
<span class="def">Dipole:</span>
<div class="def_content">
    <p>
        A point dipole describes the electrostatic ﬁeld of two point charges with equal magnitude, but opposite sign at
        a mutual distance $a\to 0$. Point dipole sites are labelled throughout the database with a ’d’. The magnitude of
        a dipole moment is deﬁned by $\mu=qa$. The electrostatic interaction between two point dipoles with the moments
        $\mu_i$ and $\mu_j$ at a distance $r_{ij}$ is given by:
    </p>
    <div class="normen_equa">
        \begin{equation}
        u_{ij}^\mathrm{dd}(r_{ij},\theta_i,\theta_j,\phi_{ij},\mu_i,\mu_j)=\frac{1}{4\pi\epsilon_0}\frac{\mu_i\mu_j}{r^3_{ij}}\left[(\sin(\theta_i)\sin(\theta_j)\cos(\phi_{ij})-2\cos(\theta_i)\cos(\theta_j)\right],
        \end{equation}
    </div>
    <p>
        where the angles $\theta_i$, $\theta_j$ and $\phi_{ij}$ indicate the relative angular orientation of the two
        point dipoles.
    </p>
</div>

<!-- Defination1 : Quadrupole-->
<span class="def">Quadrupole:</span>
<div class="def_content">
    <p>
        A linear point quadrupole describes the electrostatic ﬁeld (sf. ﬁg. 4) induced either by two collinear point
        dipoles with the same moment, but opposite orientation at a distance $a\to 0$ or four point charges. Point
        quadrupole sites are labelled as ’q’ in the database. The magnitude of a point-quadrupole $Q$ is deﬁned as $Q =
        2qd^2$, where $q$ is the magnitude of the three similar charges and $d$ their distance. The interaction
        potential is given by:
    </p>
    <div class="normen_equa">

        $$\begin{eqnarray}
        u_{ij}^\mathrm{qq}(r_{ij},\theta_i,\theta_j,\phi_{ij},Q_i,Q_j) =
        \frac{1}{4\pi\epsilon_0}\frac{3}{4}\frac{Q_iQ_j}{r^5_{ij}}\left[1-5((\cos(\theta_i)^2+\cos(\theta_i)^2) \\[5pt]
        ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-15(\cos(\theta_i))^2(\cos(\theta_j))^2+2(\sin(\theta_i)\sin(\theta_j)\cos(\phi_{ij})-4\cos(\theta_i)\cos(\theta_j))^2\right],
        \end{eqnarray}$$

    </div>

    <p>
        where the angles $\theta_i$, $\theta_j$ and $\phi_{ij}$ indicate the relative angular orientation of the two
        point quadrupoles.
    </p>
    <div class="figure"><img src="img/nomen/engin.PNG"/></div>
    <p class="figure_label">Figure 4: Charge distribution of a linear quadrupole.</p>
</div>
